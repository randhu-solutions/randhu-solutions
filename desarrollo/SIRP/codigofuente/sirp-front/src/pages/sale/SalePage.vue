<template>
  <div id="social">
    <vue-perfect-scrollbar
      class="drawer-menu--scroll"
      :settings="scrollSettings"
    >
      <v-container fluid>
        <v-row>
          <v-toolbar card dense color="transparent">
            <v-toolbar-title><h4>Venta</h4></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn small color="primary" @click="onDialogUpdate(null, 'new')">
              Agregar producto
            </v-btn>
          </v-toolbar>
          <template v-if="!loading">
            <div class="text-xs-center">
              <v-progress-circular
                :size="50"
                color="primary"
                indeterminate
              ></v-progress-circular>
            </div>
          </template>
          <template v-else>
            <v-col cols="12">
              <v-card>
                <v-card-title>
                  <!-- h4 class="subheading mb-2">Invitados frecuentes</!-- -->
                  <v-spacer></v-spacer>
                  <v-text-field
                    v-model="searchInvitation"
                    append-icon="search"
                    label="Buscar por Nombre"
                    single-line
                    hide-details
                    class="mt-0 pt-0"
                  ></v-text-field>
                </v-card-title>
                <v-data-table
                  :headers="headers"
                  :items="products"
                  :search="searchInvitation"
                  hide-actions
                  class="elevation-2"
                >
                  <template v-slot:headers="props">
                    <tr>
                      <th class="text-xs-left">Nombre</th>
                      <th class="text-xs-left" width="150">Marca</th>
                      <th class="text-xs-left" width="100">Código</th>
                      <th class="text-xs-left" width="100">Precio</th>
                      <th class="text-xs-left" width="80">Acción</th>
                    </tr>
                  </template>
                  <template slot="items" slot-scope="props">
                    <td class="text-xs-left">
                      {{ props.item.product_name }}
                    </td>
                    <td class="text-xs-left">
                      {{ props.item.brand.brand_name }}
                    </td>
                    <td class="text-xs-left">{{ props.item.product_code }}</td>
                    <td class="text-xs-left">
                      {{ `S/. ${props.item.price}` }}
                    </td>
                    <td class="text-xs-right">
                      <v-menu bottom left>
                        <template v-slot:activator="{ on }">
                          <v-btn icon v-on="on">
                            <v-icon>more_vert</v-icon>
                          </v-btn>
                        </template>
                        <v-list>
                          <v-list-tile @click="openDialogDetail(props.item)">
                            <v-list-tile-title>Ver detalle</v-list-tile-title>
                          </v-list-tile>
                          <v-list-tile
                            @click="onDialogUpdate(props.item, 'edit')"
                          >
                            <v-list-tile-title>Editar</v-list-tile-title>
                          </v-list-tile>
                          <v-list-tile @click="openDialogDelete(props.item)">
                            <v-list-tile-title>Eliminar</v-list-tile-title>
                          </v-list-tile>
                        </v-list>
                      </v-menu>
                    </td>
                  </template>
                </v-data-table>
              </v-card>
            </v-col>
          </template>
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
      searchInvitation: "",
      dialogDelete: false,
      dialogDetail: false,
      dialogAdd: false,
      loadDelete: false,
      selectedItem: {},
      typeDialog: "new",
      relation: ["Amigo", "Familiar", "Vecino"],
      headers: [
        { text: "Nombre y Apellido", value: "name" },
        { text: "DNI", value: "dni" },
        { text: "Fecha de Ingreso" },
        { text: "Correo", value: "email" },
        { text: "Acción" }
      ]
    };
  },
  computed: {
    ...mapState({
      loading: state => state.products.loading,
      products: state => state.products.items,
      currentUser: state => state.session.currentUser
    })
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
