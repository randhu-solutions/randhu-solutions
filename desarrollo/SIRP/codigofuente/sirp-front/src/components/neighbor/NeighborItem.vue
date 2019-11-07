<template>
  <div class="name-card">
    <v-card ref="card">
      <v-responsive class="text-sm-right">
        <v-menu bottom left>
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on">
              <v-icon>more_vert</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-tile>
              <v-list-tile-title @click="$emit('detail', neighbor)"
                >Ver</v-list-tile-title
              >
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-title @click="$emit('delete', neighbor)"
                >Eliminar</v-list-tile-title
              >
            </v-list-tile>
          </v-list>
        </v-menu>
      </v-responsive>
      <v-card-text>
        <div class="layout ma-0 align-center column">
          <v-avatar :size="96" color="primary">
            <img
              :src="`https://picsum.photos/96/96?random=${index + 1}`"
              :alt="neighbor.user.name"
            />
          </v-avatar>
          <div class="flex text-sm-center">
            <div class="subheading">{{ neighbor.user.name }}</div>
            <div class="caption">Invitado de: {{ neighbor.user.name }}</div>
          </div>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
export default {
  name: "NeighborItem",
  props: {
    neighbor: {
      type: Object,
      default() {
        return {};
      }
    },
    index: {
      type: Number,
      default: 0
    }
  },
  data: () => ({}),
  // computed: {
  //   showAvatar () {
  //     return this.guest.avatar !== null && this.guest.avatar.src;
  //   }
  // },
  methods: {}
};
</script>
<style lang="scss" scoped>
.caption,
.subheading {
  font-weight: 200;
}
</style>
