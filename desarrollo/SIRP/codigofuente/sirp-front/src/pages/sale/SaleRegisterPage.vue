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
              <v-card-title class="pt-0">
                <v-btn small color="primary" @click="onSubmitSale()">
                  Realizar venta
                </v-btn>
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

    <v-dialog v-model="dialogSuccess" persistent max-width="450px">
      <v-card>
        <v-card-title>
          <span class="subheading">Venta registrada</span>
        </v-card-title>
        <v-card-text>
          <div>
            Se realizó con exito la venta.
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" dark flat @click="closeSuccess()"
            >Cerrar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { isEmpty, debounce, findIndex } from "lodash";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import { mapState, mapActions } from "vuex";

export default {
  name: "SalePage",
  components: {
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
        { text: "Cantidad", value: "quantity" },
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
      dialogSuccess: false
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
          count += parseFloat(`${e.price}`) * e.quantity;
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
  methods: {
    onSubmitSale() {
      let items = [];
      this.listProduct.forEach(e => {
        items.push({
          product_id: e.product_id,
          product_description: e.product_name,
          quantity: 1,
          price_unit: e.price,
          price_total: e.price
        });
      });
      const payload = {
        user_id: this.currentUser.user_id,
        serie: "S001",
        igv: 18,
        total: `${this.priceTotal}`,
        items
      };
      this.axios
        .post("venta/crear", payload)
        .then(snap => {
          console.log("response", snap.data.response);
          this.dialogSuccess = true;
        })
        .catch(console.log)
        .finally(() => (this.fetchingRecommendations = false));
    },
    closeSuccess() {
      this.dialogSuccess = false;
      this.listProduct = [];
      this.currentRecommendations = [];
    },
    onChangeSelect(event) {
      if (!event) {
        return false;
      }
      this.pushProduct(event, true);
      this.fetchRecommendations(event);
    },
    pushProduct(product, flag = false) {
      let quantity = 1;
      const payload = {
        product_name: product.product_name,
        price: product.product_price,
        product_id: product.product_id,
        quantity
      };
      const index = findIndex(this.listProduct, {
        product_id: product.product_id
      });
      if (index >= 0) {
        this.$set(
          this.listProduct[index],
          "quantity",
          this.listProduct[index].quantity + 1
        );
      } else {
        product["quantity"] = 1;
        this.listProduct.push(flag ? product : payload);
      }
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
