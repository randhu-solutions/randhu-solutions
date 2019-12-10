<template>
  <v-card>
    <v-card-title class="pb-0">
      <span class="subheading">Agregar Categoría</span>
    </v-card-title>
    <v-card-text>
      <v-container fluid grid-list-md class="px-0 py-0">
        <v-layout row wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.category_name"
              label="Nombre*"
              placeholder="Ingrese nombre"
              required
              hide-details
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn flat @click="$emit('close')">Cerrar</v-btn>
      <v-btn color="blue darken-1" :loading="loadForm" flat @click="onSubmit"
        >Guardar</v-btn
      >
    </v-card-actions>
  </v-card>
</template>
<script>
import { isEmpty, cloneDeep } from "lodash";
import { mapState } from "vuex";

export default {
  name: "ProductForm",
  props: {
    type: {
      type: String,
      default: "new"
    },
    category: {
      type: Object,
      default() {
        return {};
      }
    }
  },
  data() {
    return {
      loadForm: false,
      selectedBrand: null,
      selectedCategory: null,
      form: {
        category_name: null
      }
    };
  },
  computed: {
    ...mapState({
      brands: state => state.brand.items,
      categories: state => state.category.items
    })
  },
  watch: {
    category(val) {
      if (val && this.type === "edit") {
        this.form = cloneDeep(this.category);
      }
    }
  },
  created() {
    if (this.type === "edit") {
      this.form = cloneDeep(this.category);
    }
  },
  methods: {
    onSubmit() {
      if (this.type === "edit") {
        this.loadForm = true;
        this.$store.dispatch("editInvitation", this.form).then(() => {
          this.loadForm = false;
          this.$emit("submit");
        });
      } else {
        if (isEmpty(this.form.category_name)) {
          return false;
        }
        this.loadForm = true;
        this.$store.dispatch("createCategory", this.form).then(() => {
          this.loadForm = false;
          this.$emit("submit");
        });
      }
    }
  }
};
</script>
