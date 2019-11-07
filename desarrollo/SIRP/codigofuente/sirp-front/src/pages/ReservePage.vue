<template>
  <div id="social">
    <v-container grid-list-xl fluid>
      <v-layout row wrap>
        <v-toolbar card dense color="transparent">
          <v-toolbar-title><h4>Mis Reservaciones</h4></v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn
            v-if="currentUser.role_name === 'Residente'"
            small
            color="primary"
            @click="onEventAdd()"
            >Agregar Reserva</v-btn
          >
        </v-toolbar>
        <template v-if="!loading">
          <div class="text-xs-center">
            <v-progress-circular
              :size="50"
              color="primary"
              indeterminate
            ></v-progress-circular>
          </div>
        </template>
        <template v-else>
          <v-flex sm12 lg12 class="pa-2 mb-3 feature-pane elevation-1">
            <!-- <v-btn
              fab
              outline
              small
              absolute
              left
              color="primary"
              @click="$refs.calendarBooking.prev()"
            >
              <v-icon dark>
                keyboard_arrow_left
              </v-icon>
            </v-btn>
            <v-btn
              fab
              outline
              small
              absolute
              right
              color="primary"
              @click="$refs.calendarBooking.next()"
            >
              <v-icon dark>
                keyboard_arrow_right
              </v-icon>
            </v-btn> -->
            <v-container grid-list-xl fluid class="py-0">
              <v-layout row wrap>
                <v-flex xs6>
                  <v-select
                    v-model="selectedArea"
                    :items="areas"
                    item-text="name"
                    item-value="id"
                    label="Área comun"
                    placeholder="Seleccione un área"
                    hide-details
                  ></v-select>
                </v-flex>
                <v-flex xs6>
                  <v-menu
                    ref="menu"
                    v-model="startMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="dateStart"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="dateStart"
                        label="Picker in menu"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="dateStart"
                      type="month"
                      no-title
                      locale="es"
                      scrollable
                    >
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="startMenu = false"
                        >Cerrar</v-btn
                      >
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.menu.save(dateStart)"
                        >Guardar</v-btn
                      >
                    </v-date-picker>
                  </v-menu>
                </v-flex>
                <!-- <v-flex xs12>
                  <v-menu
                    ref="pickerEnd"
                    v-model="showPickerEnd"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="dateEnd"
                    transition="scale-transition"
                    min-width="290px"
                    lazy
                    offset-y
                    full-width
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="dateEnd"
                        class="mt-0"
                        label="Fecha de fin"
                        palceholder="Seleccione una fecha"
                        prepend-icon="event"
                        readonly
                        hide-details
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="dateEnd" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn
                        flat
                        color="primary"
                        @click="showPickerEnd = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.pickerEnd.save(dateEnd)"
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-flex> -->
              </v-layout>
            </v-container>
          </v-flex>
          <v-flex sm12 lg12 class="pl-3 pt-0">
            <v-sheet height="500" class="elevation-1">
              <v-calendar
                ref="calendarBooking"
                v-model="dateCalendar"
                locale="es"
                :value="today"
                type="month"
                :now="today"
              >
                <template v-slot:day="{ date }">
                  <template v-for="reservation in eventsMap[date]">
                    <v-menu
                      :key="reservation.id"
                      v-model="reservation.open"
                      full-width
                      offset-x
                    >
                      <template v-slot:activator="{ on }">
                        <div
                          v-if="!reservation.time"
                          v-ripple
                          class="my-event"
                          v-on="on"
                        >
                          <template v-if="validateEvents(reservation)">
                            {{ reservation.events[0].title }}
                          </template>
                          <template v-else>
                            {{ reservation.user.name }}
                          </template>
                        </div>
                      </template>
                      <v-card color="grey lighten-4" min-width="350px" flat>
                        <v-toolbar dense color="primary" dark>
                          <v-toolbar-title>
                            <template v-if="validateEvents(reservation)">
                              {{ reservation.events[0].title }}
                            </template>
                            <template v-else>
                              {{ reservation.user.name }}
                            </template>
                          </v-toolbar-title>
                        </v-toolbar>
                        <v-card-title
                          v-if="validateEvents(reservation)"
                          primary-title
                        >
                          <p></p>
                          <span>{{ reservation.events[0].details }}</span>
                        </v-card-title>
                        <v-card-actions>
                          <v-btn small flat color="secondary">
                            Cerrar
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-menu>
                  </template>
                </template>
              </v-calendar>
            </v-sheet>
          </v-flex>
        </template>
      </v-layout>
    </v-container>

    <v-dialog v-model="dialogAdd" persistent max-width="750px">
      <reserve-form
        :reserve="selectedBooking"
        @close="dialogAdd = false"
        @submit="dialogAdd = false"
      />
    </v-dialog>

    <v-dialog v-model="dialogDelete" persistent max-width="450px">
      <v-card v-if="dialogDelete">
        <v-card-title>
          <span class="subheading">Eliminar Invitado</span>
        </v-card-title>
        <v-card-text>
          <div>
            ¿Estás seguro que deseas eliminar a {{ selectedItem.name }}?
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="dialogDelete = false">No</v-btn>
          <v-btn color="blue darken-1" flat @click="dialogDelete = false"
            >Sí</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { isEmpty } from "lodash";
import { mapState, mapActions } from "vuex";
import ReserveForm from "@/components/reserve/ReserveForm";

export default {
  name: "ReservePage",
  components: {
    ReserveForm
  },
  data() {
    return {
      selectedBooking: null,
      dateEnd: "2019-01-27",
      dateStart: new Date().toISOString().substr(0, 7),
      startMenu: false,
      showPickerEnd: false,
      type: "month",
      minWeeks: 1,
      nowMenu: false,
      now: null,
      maxDays: 7,
      today: "2019-01-08",
      styleInterval: "default",
      selectedArea: null,
      searchInvitation: null,
      dialogDelete: false,
      dialogDetail: false,
      dialogAdd: false,
      selectedItem: {},
      headers: [
        { text: "Nombre y Apellido", align: "left" },
        { text: "DNI" },
        { text: "Fecha de llegada" },
        { text: "Action" }
      ]
    };
  },
  computed: {
    ...mapState({
      areas: state => state.areas.items,
      loading: state => state.invitations.loading,
      reservations: state => state.reserve.items,
      currentUser: state => state.session.currentUser
    }),
    eventsMap() {
      const map = {};
      this.reservations.forEach(e =>
        (map[e.reservation_end_date] = map[e.reservation_end_date] || []).push(
          e
        )
      );
      return map;
    },
    dateCalendar() {
      return `${this.dateStart}-01`;
    }
  },
  async mounted() {
    await this.fetchAreas();
    await this.fetchInvitations();
    await this.fetchReservations();
  },
  methods: {
    ...mapActions(["fetchAreas", "fetchInvitations", "fetchReservations"]),
    open(event) {
      alert(event.title);
    },
    showIntervalLabel(interval) {
      return interval.minute === 0;
    },
    onEventAdd() {
      this.dialogAdd = true;
    },
    openDialogDelete(item) {
      this.selectedItem = item;
      this.dialogDelete = true;
    },
    openDialogDetail(item) {
      this.selectedItem = item;
      this.dialogDetail = true;
    },
    onSearch() {
      if (!this.searchInvitation) return;
      console.log("search!", this.searchInvitation);
    },
    validateEvents(reserve) {
      return !isEmpty(reserve.events);
    }
  }
};
</script>
<style scoped lang="scss">
.my-event {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  border-radius: 2px;
  background-color: #1867c0;
  color: #ffffff;
  border: 1px solid #1867c0;
  width: 100%;
  font-size: 12px;
  padding: 3px;
  cursor: pointer;
  margin-bottom: 1px;
}
.feature-pane {
  position: relative;
  padding-top: 30px;
  background-color: #fff;
  // box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}
/*
.day-header {
  margin: 0px 2px 2px 2px;
  padding: 2px 6px;
  background-color: #1867c0;
  color: #ffffff;
  border: 1px solid #1867c0;
  border-radius: 2px;
  user-select: none;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}

.day-body {
  position: absolute;
  top: 400px;
  height: 36px;
  margin: 2px;
  padding: 2px 6px;
  background-color: #1867c0;
  color: #ffffff;
  border: 1px solid #1867c0;
  border-radius: 2px;
  left: 0;
  right: 0;
  user-select: none;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}

.day {
  position: relative;
  height: 24px;
  margin: 0px;
  padding: 0px 6px;
  background-color: #1867c0;
  color: #ffffff;
  border: 1px solid #1867c0;
  border-radius: 2px;
  left: 0;
  right: 0;
  user-select: none;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
*/
</style>
