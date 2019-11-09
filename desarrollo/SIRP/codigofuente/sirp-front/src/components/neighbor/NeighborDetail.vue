<template>
  <v-card>
    <v-card-title>
      <span class="subheading">{{ neighbor.user.name }}</span>
    </v-card-title>
    <v-card-text>
      <v-list two-line class="pa-0">
        <v-list-tile>
          <v-list-tile-action>
            <v-icon color="indigo">email</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ neighbor.user.email }}</v-list-tile-title>
            <v-list-tile-sub-title>Email</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile>
          <v-list-tile-action>
            <v-icon color="indigo">home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{
              neighbor.department.number
            }}</v-list-tile-title>
            <v-list-tile-sub-title>Departamento</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" flat @click="$emit('close')">Cerrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "NeighborDetail",
  props: {
    neighbor: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      relation: ["Amigo", "Familiar", "Vecino"]
    };
  }
};
</script>
