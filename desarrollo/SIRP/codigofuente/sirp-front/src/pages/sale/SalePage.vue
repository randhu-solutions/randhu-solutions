<template>
  <div id="social">
    <vue-perfect-scrollbar
      class="drawer-menu--scroll"
      :settings="scrollSettings"
    >
      <v-container fluid>
        <v-row>
          <v-col cols="12">
            <h4>Ventas</h4>
            <v-spacer></v-spacer>
            <v-btn small color="primary" @click="onDialogUpdate(null, 'new')">
              Agregar venta
            </v-btn>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="8">
            <v-card>
              <v-card-title>
                <v-row>
                  <v-col cols="12" class="pt-0">
                    <v-autocomplete
                      v-model="selectedSearch"
                      :loading="loadSearch"
                      :items="itemsListSearch"
                      :search-input.sync="inputSearch"
                      item-text="product_name"
                      item-value="product_id"
                      hide-no-data
                      hide-details
                      clearable
                      outlined
                      return-object
                      class="mt-0"
                      placeholder="Ingresa tu búsqueda"
                      @click:clear="clearSearch"
                      @change="onChangeSelect"
                    ></v-autocomplete>
                  </v-col>
                  <v-col cols="12">
                    <v-data-table
                      :headers="headers"
                      :items="listProduct"
                      item-key="id"
                      hide-default-footer
                      class="elevation-2"
                    >
                      <template v-slot:headers="{ props: { headers } }">
                        <thead>
                          <tr>
                            <th v-for="item in headers" :key="item.value">
                              <span :key="`span_${item.value}`">{{
                                item.text
                              }}</span>
                            </th>
                          </tr>
                        </thead>
                      </template>
                      <template v-slot:item.action="{ item }">
                        <v-menu bottom left>
                          <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on">
                              <v-icon>more_vert</v-icon>
                            </v-btn>
                          </template>
                          <v-list>
                            <v-list-item @click="deleteListProduct(item)">
                              <v-list-item-title>Eliminar</v-list-item-title>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                      </template>
                    </v-data-table>
                  </v-col>

                  <v-col cols="12">
                    <h5>Productos Recomendados</h5>

                    <v-data-table
                      :headers="headersRecommendation"
                      :items="currentRecommendations"
                      item-key="id"
                      hide-default-footer
                      class="elevation-2"
                      :loading="fetchingRecommendations"
                    >
                      <template v-slot:headers="{ props: { headers } }">
                        <thead>
                          <tr>
                            <th v-for="item in headers" :key="item.value">
                              <span :key="`span_${item.value}`">{{
                                item.text
                              }}</span>
                            </th>
                          </tr>
                        </thead>
                      </template>
                      <template v-slot:item.action="{ item }">
                        <v-menu bottom left>
                          <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on">
                              <v-icon>more_vert</v-icon>
                            </v-btn>
                          </template>
                          <v-list>
                            <v-list-item @click="pushProduct(item, false)">
                              <v-list-item-title>Agregar</v-list-item-title>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                      </template>
                    </v-data-table>
                  </v-col>
                </v-row>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col cols="4">
            <v-card>
              <v-card-title>
                <h5 class="d-block w-100">Total de la venta</h5>
              </v-card-title>
              <v-card-title class="pt-0">
                <p class="mb-0">
                  Precio total: <b>{{ priceTotal }}</b>
                </p>
              </v-card-title>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </vue-perfect-scrollbar>

    <v-dialog v-model="dialogDelete" persistent max-width="450px">
      <v-card v-if="dialogDelete">
        <v-card-title>
          <span class="subheading">Eliminar Invitado</span>
        </v-card-title>
        <v-card-text>
          <div>
            ¿Estás seguro que deseas eliminar a {{ selectedItem.name }}?
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="dialogDelete = false">No</v-btn>
          <v-btn
            color="blue darken-1"
            :loading="loadDelete"
            flat
            @click="onDelete()"
            >Sí</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { isEmpty, debounce } from "lodash";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import { mapState, mapActions } from "vuex";
import ProductForm from "@/components/product/ProductForm";

export default {
  name: "SalePage",
  components: {
    ProductForm,
    VuePerfectScrollbar
  },
  data() {
    return {
      scrollSettings: {
        maxScrollbarLength: 160
      },
      selectedSearch: null,
      loadSearch: false,
      itemsListSearch: [],
      inputSearch: null,
      searchInvitation: "",
      dialogDelete: false,
      dialogDetail: false,
      dialogAdd: false,
      loadDelete: false,
      selectedItem: {},
      typeDialog: "new",
      headers: [
        { text: "Nombre", value: "product_name" },
        { text: "Precio", value: "price" },
        { text: "Acción", value: "action" }
      ],
      headersRecommendation: [
        { text: "Nombre", value: "product_name" },
        { text: "Precio", value: "product_price" },
        { text: "Acción", value: "action" }
      ],
      listProduct: [],
      fetchingRecommendations: false,
      currentRecommendations: [],
    };
  },
  computed: {
    ...mapState({
      currentUser: state => state.session.currentUser
    }),
    priceTotal() {
      if (this.listProduct.length) {
        let count = 0.0;
        this.listProduct.forEach(e => {
          count += parseFloat(`${e.price}`);
        });
        return count;
      }
      return 0;
    }
  },
  watch: {
    inputSearch: debounce(function(newVal) {
      if (!isEmpty(newVal)) {
        this.getSearchList();
      } else {
        this.itemsListSearch = [];
      }
    }, 100),
  },
  methods: {
    onChangeSelect(event) {
      if (!event) {
        return false;
      }
      this.pushProduct(event, true);
      this.fetchRecommendations(event);
    },
    pushProduct(product, flag = false) {
      const payload = {
        product_name: product.product_name,
        price: product.product_price,
        product_id: product.product_id
      }
      this.listProduct.push(flag ? product : payload);
    },
    fetchRecommendations({ product_id }) {
      this.fetchingRecommendations = true;
      const path = "producto/recomendado";
      const payload = { product_id };
      this.axios
        .post(path, {
          product_id
        })
        .then(snap => {
          console.log("response", snap.data.response);
          this.currentRecommendations = snap.data.response;
        })
        .catch(console.log)
        .finally(() => (this.fetchingRecommendations = false));
    },
    clearSearch() {
      // this.loadSearch = false;
      this.inputSearch = null;
      this.itemsListSearch = [];
    },
    deleteListProduct(product) {
      this.listProduct.filter(item => item.product_id !== product.product_id);
    },
    getSearchList() {
      if (isEmpty(this.inputSearch)) {
        return false;
      }
      this.loadSearch = true;
      const payload = {
        search: this.inputSearch
      };
      this.axios
        .post("producto/buscar", payload)
        .then(snap => {
          this.itemsListSearch = snap.data.response;
          this.loadSearch = false;
        })
        .catch(error => console.log(error));
    },
    onDialogUpdate(item = null, type) {
      if (item) {
        this.typeDialog = type;
      }
      this.selectedItem = item;
      this.dialogAdd = true;
    },
    openDialogDelete(item) {
      this.selectedItem = item;
      this.dialogDelete = true;
    },
    onDelete() {
      this.loadDelete = true;
      this.$store
        .dispatch("deleteInvitation", this.selectedItem.id)
        .then(() => {
          this.loadDelete = false;
          this.dialogDelete = false;
          this.selectedItem = {};
        });
    },
    openDialogDetail(item) {
      this.selectedItem = item;
      this.dialogDetail = true;
    }
  }
};
</script>
