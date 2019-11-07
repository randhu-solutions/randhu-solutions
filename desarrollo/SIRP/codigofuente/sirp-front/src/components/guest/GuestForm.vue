<template>
  <v-card>
    <v-card-title>
      <span class="subheading">Agregar invitado</span>
    </v-card-title>
    <v-card-text>
      <v-container fluid grid-list-md class="px-0 py-0">
        <v-layout row wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.name"
              label="Nombre y Apellido*"
              placeholder="Ingrese su nombre"
              required
              hide-details
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.email"
              label="Correo electrónico"
              placeholder="Ingrese correo"
              required
              hide-details
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.dni"
              label="DNI*"
              mask="########"
              maxlength="8"
              placeholder="Ingrese DNI"
              required
              hide-details
            ></v-text-field>
          </v-flex>
          <template v-if="from !== 'Reserve'">
            <v-flex xs12>
              <v-checkbox
                v-model="form.regular_visitor"
                hide-details
                class="mt-0 pt-0"
                label="¿Es visitante regular?"
              ></v-checkbox>
            </v-flex>
            <v-flex xs12>
              <v-container fluid grid-list-md class="px-0 py-0">
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-dialog
                      ref="datePicker"
                      v-model="showDatePicker"
                      :return-value.sync="datePicker"
                      persistent
                      lazy
                      full-width
                      width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="datePicker"
                          label="Fecha de ingreso"
                          placeholder="Ingrese fecha"
                          prepend-icon="event"
                          :disabled="form.regular_visitor"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="datePicker" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn
                          flat
                          color="primary"
                          @click="showDatePicker = false"
                          >Cerrar</v-btn
                        >
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.datePicker.save(datePicker)"
                          >Guardar</v-btn
                        >
                      </v-date-picker>
                    </v-dialog>
                  </v-flex>
                  <v-flex xs6>
                    <v-dialog
                      ref="timePicker"
                      v-model="showTimePicker"
                      :return-value.sync="timePicker"
                      persistent
                      lazy
                      full-width
                      width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="timePicker"
                          label="Hora de ingreso"
                          placeholder="Seleccione una hora"
                          prepend-icon="access_time"
                          :disabled="form.regular_visitor"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-time-picker
                        v-if="showTimePicker"
                        v-model="timePicker"
                        full-width
                      >
                        <v-spacer></v-spacer>
                        <v-btn
                          flat
                          color="primary"
                          @click="showTimePicker = false"
                          >Cerrar</v-btn
                        >
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.timePicker.save(timePicker)"
                          >Guardar</v-btn
                        >
                      </v-time-picker>
                    </v-dialog>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-flex>
            <v-flex xs12>
              <v-textarea
                v-model="form.comment"
                label="Comentario"
                placeholder="Ingrese un comentario sobre el invitado"
              ></v-textarea>
            </v-flex>
          </template>
        </v-layout>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn flat @click="$emit('close')">Cerrar</v-btn>
      <v-btn color="blue darken-1" flat @click="onSubmitInvitation"
        >Guardar</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script>
import { isEmpty, cloneDeep } from "lodash";
export default {
  name: "GuestForm",
  props: {
    type: {
      type: String,
      default: "new"
    },
    guest: {
      type: Object,
      default() {
        return {};
      }
    },
    from: {
      type: String,
      default: "Guest"
    }
  },
  data() {
    return {
      loading: false,
      showDatePicker: false,
      showTimePicker: false,
      timePicker: null,
      datePicker: null,
      form: {
        name: null,
        email: null,
        dni: null,
        check: false,
        event_id: null,
        regular_visitor: false,
        invitation_date: null,
        comment: null
      }
    };
  },
  watch: {
    guest(val) {
      if (val && this.type === "edit") {
        this.form = cloneDeep(this.guest);
      }
    }
  },
  created() {
    if (this.type === "edit") {
      this.form = cloneDeep(this.guest);
    }
  },
  methods: {
    onSubmitInvitation() {
      if (this.from === "Reserve") {
        this.form.regular_visitor = true;
      }
      if (this.type === "edit") {
        this.$store.dispatch("editInvitation", this.form).then(() => {
          this.$emit("submit");
        });
      } else {
        if (isEmpty(this.form.name) || isEmpty(this.form.dni)) {
          return false;
        }
        if (
          !this.form.regular_visitor &&
          (isEmpty(this.datePicker) || isEmpty(this.timePicker))
        ) {
          return true;
        }
        this.form.invitation_date = `${this.datePicker} ${this.timePicker}:00`;
        this.$store.dispatch("createInvitation", this.form).then(() => {
          this.$emit("submit");
        });
      }
    }
  }
};
</script>
