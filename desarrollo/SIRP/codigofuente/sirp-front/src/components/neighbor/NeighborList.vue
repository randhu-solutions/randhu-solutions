<template>
  <v-data-table
    :headers="headers"
    :items="residents"
    :total-items="totalResidents"
    :loading="loading"
    class="elevation-0 table-striped"
    :pagination="pagination"
    @update:pagination="$emit('update:pagination', $event)"
  >
    <template slot="items" slot-scope="{ item }">
      <td>
        {{ item.user.name }}
      </td>
      <td>{{ item.user.email }}</td>
      <td>{{ item.department.number }}</td>
      <td>
        <v-menu bottom left>
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on">
              <v-icon>more_vert</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-tile @click="$emit('item-view', item)">
              <v-list-tile-title>Ver detalle</v-list-tile-title>
            </v-list-tile>

            <v-list-tile @click="$emit('item-generate-password', item)">
              <v-list-tile-title>Generar nueva contraseña</v-list-tile-title>
            </v-list-tile>

            <v-list-tile @click="$emit('item-delete', item)">
              <v-list-tile-title>Eliminar</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </td>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: "NeighborList",
  props: {
    residents: {
      type: Array,
      default: () => []
    },
    totalResidents: {
      type: Number,
      default: 0
    },
    loading: {
      type: Boolean,
      default: false
    },
    pagination: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      headers: [
        { text: "Nombre y Apellido", align: "left", sortable: false },
        { text: "Email", sortable: false },
        { text: "Departamento", sortable: false },
        { text: "", sortable: false }
      ]
    };
  }
};
</script>
