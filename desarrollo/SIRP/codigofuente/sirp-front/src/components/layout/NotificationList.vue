<template>
  <v-card class="elevation-0">
    <v-toolbar card dense color="transparent">
      <v-toolbar-title>
        <h4>Notificaciones</h4>
      </v-toolbar-title>
    </v-toolbar>
    <v-divider></v-divider>
    <v-card-text class="pa-0">
      <v-list two-line class="pa-0">
        <template v-for="(item, index) in items">
          <div :key="index">
            <v-list-tile avatar @click="handleClick(notifications[index])">
              <v-list-tile-avatar :color="item.color">
                <v-icon dark>{{ item.icon }}</v-icon>
              </v-list-tile-avatar>
              <v-list-tile-content class="notification-content">
                <v-list-tile-sub-title>{{ item.title }}</v-list-tile-sub-title>
              </v-list-tile-content>
              <v-list-tile-action class="caption">
                {{ item.timeLabel }}
              </v-list-tile-action>
            </v-list-tile>
            <v-divider v-if="index < items.length - 1" />
          </div>
        </template>
      </v-list>
      <v-divider></v-divider>
      <v-btn block flat class="ma-0">All</v-btn>
      <v-divider></v-divider>
    </v-card-text>
  </v-card>
</template>

<script>
import moment from "moment";

export default {
  props: {
    notifications: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    items() {
      return this.notifications.map(this.formatNotification);
    }
  },
  methods: {
    handleClick({ type, data, id, read_at }) {
      if (!read_at) this.$emit("read-notification", { id });
      if (type === "App\\Notifications\\TicketCommented") {
        this.$router.push({
          name: "SingleTicketPage",
          params: { id: data.comment.ticket.id }
        });
      }
    },
    formatNotification({ type, data, created_at }) {
      if (type === "App\\Notifications\\TicketCommented") {
        const { comment } = data;
        return {
          title: `${comment.user.name} comentó en el ticket #${comment.ticket.id}`,
          timeLabel: moment(created_at).fromNow(),
          icon: "email",
          color: "red"
        };
      }
      return {};
    }
  }
};
</script>

<style>
.notification-content {
  margin-right: 10px;
}
</style>
