<template>
  <v-toolbar color="primary" fixed dark app>
    <v-toolbar-title class="ml-0 pl-3">
      {{ currentUser.name }} ({{ currentUser.role_name }})
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-menu
      offset-y
      origin="center center"
      class="elelvation-1"
      :nudge-bottom="14"
      transition="scale-transition"
    >
      <v-btn slot="activator" icon flat>
        <v-badge color="red" overlap>
          <span v-if="unreadNotificationsCount" slot="badge">{{
            unreadNotificationsCount
          }}</span>
          <v-icon medium>notifications</v-icon>
        </v-badge>
      </v-btn>
      <notification-list
        :notifications="notifications"
        @read-notification="readNotification"
      />
    </v-menu>
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
  </v-toolbar>
</template>
<script>
import { mapState } from "vuex";
import NotificationList from "./NotificationList";
import axios from "../../http";

export default {
  name: "AppToolbar",
  components: {
    NotificationList
  },
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
  async mounted() {
    this.fetchNotifications();
  },
  methods: {
    onLogout() {
      this.$store.dispatch("logout").then(() => {
        this.$router.push({ name: "LoginPage" });
      });
    },
    async fetchNotifications() {
      const { data } = await axios.get("/notifications");
      this.notifications = data;
    },
    async readNotification({ id }) {
      await axios.post(`/notifications/${id}/read`);
      this.fetchNotifications();
    }
  }
};
</script>
