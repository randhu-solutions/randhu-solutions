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
                      :headers="headers"
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
                            <v-list-item @click="pushProduct(item)">
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

    <v-dialog v-model="dialogAdd" persistent max-width="600px">
      <product-form
        :type="typeDialog"
        :product="selectedItem"
        @close="dialogAdd = false"
        @submit="dialogAdd = false"
      />
    </v-dialog>

    <v-dialog v-model="dialogDetail" persistent max-width="450px">
      <v-card v-if="dialogDetail">
        <v-card-title>
          <span class="subheading text-uppercase">{{ selectedItem.name }}</span>
          <span v-if="selectedItem.dni" class="ml-2 body-1"
            >, DNI: <b>{{ selectedItem.dni }}</b></span
          >
        </v-card-title>
        <v-card-text class="pt-0">
          <v-list two-line class="pa-0">
            <v-list-tile>
              <v-list-tile-action>
                <v-icon color="indigo">mail</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>{{ selectedItem.email }}</v-list-tile-title>
                <v-list-tile-sub-title>Email</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-action>
                <v-icon color="indigo">date_range</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>{{
                  selectedItem.regular_visitor
                    ? "Invitado frecuente"
                    : selectedItem.invitation_date
                }}</v-list-tile-title>
                <v-list-tile-sub-title>Fecha de llegada</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="dialogDetail = false"
            >Cerrar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

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
        { text: "Marca", value: "brand.brand_name" },
        { text: "Código", value: "product_code" },
        { text: "Precio", value: "price" },
        { text: "Acción", value: "action" }
      ],
      listProduct: [],
      fetchingRecommendations: false,
      currentRecommendations: []
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
          count += count + parseFloat(`${e.price}`);
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
    }, 100)
  },
  created() {
    this.fetchCategory();
    this.fetchBrand();
  },
  async mounted() {
    await this.fetchProduct();
  },
  methods: {
    ...mapActions([
      "fetchProduct",
      "createProduct",
      "fetchCategory",
      "fetchBrand"
    ]),
    onChangeSelect(event) {
      if (!event) {
        return false;
      }
      this.pushProduct(event);
      this.fetchRecommendations(event);
    },
    pushProduct(product) {
      this.listProduct.push(product);
    },
    fetchRecommendations({ product_id }) {
      this.fetchingRecommendations = true;
      const path = "producto/recomendado";
      const payload = { product_id };
      this.axios
        .post("producto/buscar", {
          search: "a"
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
