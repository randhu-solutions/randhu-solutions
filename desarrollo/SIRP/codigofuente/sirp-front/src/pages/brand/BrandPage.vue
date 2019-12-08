<template>
  <div id="social">
    <vue-perfect-scrollbar
      class="drawer-menu--scroll"
      :settings="scrollSettings"
    >
      <v-container grid-list-xl fluid>
        <v-row>
          <v-col cols="12">
            <h4>Mis Marcas</h4>
            <v-spacer></v-spacer>
            <v-btn small color="primary" @click="onDialogUpdate(null, 'new')">
              Agregar marca
            </v-btn>
          </v-col>
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
                  :items="brands"
                  :search="searchInvitation"
                  item-key="id"
                  hide-default-footer
                  hide-default-header
                  class="elevation-2"
                >
                  <template v-slot:headers="{ props: { headers } }">
                    <thead>
                      <tr>
                        <th v-for="item in headers" :key="item.value">
                          <span
                            :key="`span_${item.value}`"
                            v-html="item.text"
                          ></span>
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
                        <v-list-item @click="openDialogDetail(item)">
                          <v-list-item-title>Ver detalle</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="onDialogUpdate(item, 'edit')">
                          <v-list-item-title>Editar</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="openDialogDelete(item)">
                          <v-list-item-title>Eliminar</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </template>
                </v-data-table>
              </v-card>
            </v-col>
          </template>
        </v-row>
      </v-container>
    </vue-perfect-scrollbar>

    <v-dialog v-model="dialogAdd" persistent max-width="600px">
      <brand-form
        :type="typeDialog"
        :brand="selectedItem"
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
        <v-card-title class="pb-0">
          <span class="subheading">Eliminar Invitado</span>
        </v-card-title>
        <v-card-text>
          <div>
            ¿Estás seguro que deseas eliminar a
            <b>{{ selectedItem.brand_name }}</b
            >?
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
import BrandForm from "@/components/brand/BrandForm";

export default {
  name: "BrandPage",
  components: {
    BrandForm,
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
      headers: [
        { text: "Nombre", value: "brand_name" },
        { text: "Acción", value: "action" }
      ]
    };
  },
  computed: {
    ...mapState({
      loading: state => state.brand.loading,
      brands: state => state.brand.items
    })
  },
  async mounted() {
    await this.fetchBrand();
  },
  methods: {
    ...mapActions(["fetchBrand", "deleteBrand"]),
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
      this.deleteBrand(this.selectedItem.brand_id).then(() => {
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
