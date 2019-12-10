<template>
  <v-navigation-drawer
    id="appDrawer"
    v-model="drawer"
    :clipped="$vuetify.breakpoint.lgAndUp"
    fixed
    app
    width="200"
  >
    <vue-perfect-scrollbar
      class="drawer-menu--scroll"
      :settings="scrollSettings"
    >
      <v-list dense expand>
        <!--top-level link-->
        <v-list-item 
          :to="{ name: 'DashboardPage' }"
          ripple="ripple"
          rel="noopener"
        >
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item >

        <v-list-item
          :to="{ name: 'SalePage' }"
          ripple="ripple"
          rel="noopener"
        >
          <v-list-item-content>
            <v-list-item-title>Ventas</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          :to="{ name: 'ProductPage' }"
          ripple="ripple"
          rel="noopener"
        >
          <v-list-item-content>
            <v-list-item-title>Productos</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          :to="{ name: 'CategoryPage' }"
          ripple="ripple"
          rel="noopener"
        >
          <v-list-item-content>
            <v-list-item-title>Categoria</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item :to="{ name: 'BrandPage' }" ripple="ripple" rel="noopener">
          <v-list-item-content>
            <v-list-item-title>Marcas</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </vue-perfect-scrollbar>
  </v-navigation-drawer>
</template>
<script>
import menu from "@/utils/menu";
import { mapState } from "vuex";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
export default {
  name: "AppDrawer",
  components: {
    VuePerfectScrollbar
  },
  props: {
    expanded: {
      type: Boolean,
      default: true
    }
  },
  data: () => ({
    mini: false,
    drawer: true,
    menus: menu,
    scrollSettings: {
      maxScrollbarLength: 160
    }
  }),
  computed: {
    ...mapState({
      currentUser: state => state.session.currentUser
    })
  },
  created() {
    window.getApp.$on("APP_DRAWER_TOGGLED", () => {
      this.drawer = !this.drawer;
    });
  },

  methods: {
    genChildTarget(item, subItem) {
      if (subItem.href) return;
      if (subItem.component) {
        return {
          name: subItem.component
        };
      }
      return { name: `${item.group}/${subItem.name}` };
    }
  }
};
</script>
<style lang="scss">
#appDrawer {
  overflow: hidden;
  .drawer-menu--scroll {
    height: calc(100vh - 48px);
    overflow: auto;
  }
}
</style>
