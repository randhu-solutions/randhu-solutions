<template>
  <v-app-bar
    color="primary"
    dark
    app
    :clipped-left="$vuetify.breakpoint.lgAndUp"
  >
    <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
    <v-toolbar-title class="ml-0 pl-3">
      {{ `${currentUser.profile.first_name} ${currentUser.profile.last_name}` }}
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-menu bottom left>
      <template v-slot:activator="{ on }">
        <v-btn icon large>
          <v-avatar size="30px">
            <img
              src="https://s3.amazonaws.com/uifaces/faces/twitter/ludwiczakpawel/128.jpg"
              alt="Michael Wang"
            />
          </v-avatar>
        </v-btn>
      </template>
      <v-list class="pa-0">
        <v-list-item @click="onLogout">
          <v-list-item-action>
            <v-icon>fullscreen_exit</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>CERRAR SESIÓN</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>
<script>
import { mapState } from "vuex";

export default {
  name: "AppToolbar",
  data: () => ({
    items: [
      {
        icon: "fullscreen_exit",
        href: "#",
        title: "Logout",
        click: () => {
          window.getApp.$emit("APP_LOGOUT");
        }
      }
    ],
    drawer: false,
    notifications: []
  }),
  computed: {
    ...mapState({
      currentUser: state => state.session.currentUser
    }),
    toolbarColor() {
      return this.$vuetify.options.extra.mainNav;
    },
    unreadNotificationsCount() {
      return this.notifications.filter(notification => !notification.read_at)
        .length;
    }
  },
  methods: {
    onLogout() {
      this.$store.dispatch("logout").then(() => {
        this.$router.push({ name: "LoginPage" });
      });
    }
  }
};
</script>
