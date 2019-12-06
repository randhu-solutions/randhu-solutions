<template>
  <v-app-bar color="primary" dark app :clipped-left="$vuetify.breakpoint.lgAndUp">
    <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
    <v-toolbar-title class="ml-0 pl-3">
      {{ `${currentUser.profile.first_name} ${currentUser.profile.last_name}` }}
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-menu
      offset-y
      origin="center center"
      :nudge-bottom="10"
      transition="scale-transition"
    >
      <v-btn slot="activator" icon large flat>
        <v-avatar size="30px">
          <img
            src="https://s3.amazonaws.com/uifaces/faces/twitter/ludwiczakpawel/128.jpg"
            alt="Michael Wang"
          />
        </v-avatar>
      </v-btn>
      <v-list class="pa-0">
        <v-list-tile @click="onLogout">
          <v-list-tile-action>
            <v-icon>fullscreen_exit</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>CERRAR SESIÓN</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
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
