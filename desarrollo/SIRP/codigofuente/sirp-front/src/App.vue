<template>
  <v-app id="inspire">
    <template v-if="!$route.meta.public">
      <app-drawer class="app--drawer"></app-drawer>
      <app-toolbar class="app--toolbar"></app-toolbar>
      <v-content>
        <!-- Page Header -->
        <page-header v-if="$route.meta.breadcrumb"></page-header>
        <div class="page-wrapper">
          <router-view></router-view>
        </div>
      </v-content>
    </template>
    <template v-else>
      <transition>
        <keep-alive>
          <router-view :key="$route.fullpath"></router-view>
        </keep-alive>
      </transition>
    </template>
  </v-app>
</template>
<script>
import AppDrawer from "@/layout/AppDrawer";
import AppToolbar from "@/layout/AppToolbar";
import PageHeader from "@/layout/PageHeader";

export default {
  components: {
    AppDrawer,
    AppToolbar,
    PageHeader
  },
  data: () => ({
    scrollSettings: {
      maxScrollbarLength: 160
    },
    expanded: true,
    rightDrawer: false,
    snackbar: {
      show: false,
      text: "",
      color: ""
    }
  }),

  computed: {},
  created() {
    // AppEvents.forEach(item => {
    //   this.$on(item.name, item.callback);
    // });
    window.getApp = this;
  },
  methods: {
    openThemeSettings() {
      this.$vuetify.goTo(0);
      this.rightDrawer = !this.rightDrawer;
    }
  }
};
</script>
<style lang="scss" scoped>
.setting-fab {
  top: 50% !important;
  right: 0;
  border-radius: 0;
}
.page-wrapper {
  min-height: calc(100vh - 64px - 50px - 81px);
}
</style>
