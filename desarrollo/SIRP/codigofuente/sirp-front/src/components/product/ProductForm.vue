<template>
  <v-card>
    <v-card-title class="pb-0">
      <span class="subheading">Agregar Producto</span>
    </v-card-title>
    <v-card-text>
      <v-container fluid grid-list-md class="px-0 py-0">
        <v-layout row wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.product_name"
              label="Nombre*"
              placeholder="Ingrese nombre"
              required
              hide-details
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.product_code"
              label="Código*"
              placeholder="Ingrese código"
              required
              hide-details
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-select
              v-model="selectedBrand"
              :items="brands"
              label="Marca*"
              item-text="brand_name"
              item-value="brand_id"
              placeholder="Seleccione opción"
              return-object
              hide-details
            />
          </v-flex>
          <v-flex xs12>
            <v-select
              v-model="selectedCategory"
              :items="categories"
              label="Categoría*"
              item-text="category_name"
              item-value="category_id"
              placeholder="Seleccione opción"
              return-object
              hide-details
            />
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.price"
              label="Precio*"
              placeholder="Ingrese precio"
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
      <v-btn
        color="blue darken-1"
        :loading="loadForm"
        flat
        @click="onSubmitProduct"
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
    product: {
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
        product_name: null,
        product_code: null,
        price: null,
        category_id: null,
        brand_id: null
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
    product(val) {
      if (val && this.type === "edit") {
        this.form = cloneDeep(this.product);
      }
    }
  },
  created() {
    if (this.type === "edit") {
      this.form = cloneDeep(this.product);
    }
  },
  methods: {
    onSubmitProduct() {
      if (this.type === "edit") {
        this.loadForm = true;
        this.$store.dispatch("editInvitation", this.form).then(() => {
          this.loadForm = false;
          this.$emit("submit");
        });
      } else {
        this.form.category_id = this.selectedCategory.category_id || null;
        this.form.brand_id = this.selectedBrand.brand_id || null;
        console.log(this.form);
        if (
          isEmpty(this.form.product_name) ||
          isEmpty(this.form.product_code) ||
          isEmpty(this.form.price) ||
          this.form.category_id === null ||
          this.form.brand_id === null
        ) {
          return false;
        }
        this.loadForm = true;
        this.$store.dispatch("createProduct", this.form).then(() => {
          this.loadForm = false;
          this.$emit("submit");
        });
      }
    }
  }
};
</script>
