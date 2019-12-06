<template>
  <div id="social">
    <vue-perfect-scrollbar
      class="drawer-menu--scroll"
      :settings="scrollSettings"
    >
      <v-container fluid>
        <v-row>
          <v-col cols="12">
            <h4>Mis Categorías</h4>
            <v-spacer></v-spacer>
            <v-btn small color="primary" @click="onDialogUpdate(null, 'new')">
              Agregar categoría
            </v-btn>
          </v-col>
        </v-row>
        <v-row>
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
                  :items="categories"
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
                  </template>
                </v-data-table>
              </v-card>
            </v-col>
          </template>
        </v-row>
      </v-container>
    </vue-perfect-scrollbar>

    <v-dialog v-model="dialogAdd" persistent max-width="600px">
      <category-form
        :type="typeDialog"
        :category="selectedItem"
        @close="dialogAdd = false"
        @submit="dialogAdd = false"
      />
    </v-dialog>

    <v-dialog v-model="dialogDelete" persistent max-width="450px">
      <v-card v-if="dialogDelete">
        <v-card-title class="pb-0">
          <span class="subheading">Eliminar Invitado</span>
        </v-card-title>
        <v-card-text>
          <div>
            ¿Estás seguro que deseas eliminar a
            <b>{{ selectedItem.category_name }}</b
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
import CategoryForm from "@/components/category/CategoryForm";

export default {
  name: "CategoryPage",
  components: {
    CategoryForm,
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
      headers: [{ text: "Nombre", value: "category_name" }, { text: "Acción", value: "action" }]
    };
  },
  computed: {
    ...mapState({
      loading: state => state.category.loading,
      categories: state => state.category.items
    })
  },
  async mounted() {
    await this.fetchCategory();
  },
  methods: {
    ...mapActions(["fetchCategory", "deleteCategory"]),
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
      this.deleteCategory(this.selectedItem.category_id).then(() => {
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
