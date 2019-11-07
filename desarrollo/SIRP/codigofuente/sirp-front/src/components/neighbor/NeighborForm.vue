<template>
  <v-card>
    <v-card-title>
      <span class="headline">Agregar invitado</span>
    </v-card-title>
    <v-card-text>
      <v-container grid-list-md>
        <v-layout wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.name"
              label="Nombre y Apellido"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.email"
              label="Email"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-select
              v-model="form.department_id"
              :items="departments"
              label="Departamento"
            />
          </v-flex>
        </v-layout>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" flat @click="$emit('close')">Cerrar</v-btn>
      <v-btn color="blue darken-1" flat @click="submit">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "GuestForm",
  data() {
    return {
      loading: false,
      form: {
        name: "",
        email: "",
        department_id: ""
      }
    };
  },
  computed: {
    ...mapState({
      departments: state =>
        state.departments.map(department => ({
          text: department.number.toString(),
          value: department.id
        }))
    })
  },

  mounted() {
    this.fetchDepartments();
  },
  methods: {
    ...mapActions(["fetchDepartments"]),
    submit() {
      this.$emit("submit", this.form);
    }
  }
};
</script>
