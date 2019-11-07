<template>
  <v-app id="login" class="primary">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4 lg3>
            <v-card class="elevation-1 pt-3">
              <v-card-text class="py-0">
                <div class="layout column align-center">
                  <h1 class="flex mb-4 primary--text">Randhu Solutions</h1>
                </div>
                <v-form>
                  <v-text-field
                    v-model="form.last_name"
                    name="lastname"
                    label="Nombres"
                    hide-details
                  ></v-text-field>
                  <v-text-field
                    v-model="form.first_name"
                    class="mt-2"
                    name="firstname"
                    label="Apellidos"
                    hide-details
                  ></v-text-field>
                  <v-text-field
                    v-model="form.username"
                    class="mt-2"
                    name="username"
                    label="Usuario"
                    hide-details
                  ></v-text-field>
                  <v-text-field
                    v-model="form.email"
                    class="mt-2"
                    name="login"
                    label="Correo"
                    type="email"
                    hide-details
                  ></v-text-field>
                  <v-text-field
                    v-model="form.password"
                    class="mt-2"
                    name="password"
                    label="Contraseña"
                    type="password"
                    hide-details
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions class="d-block pt-1">
                <div class="d-block">
                  <p style="text-align: right;">
                    <router-link :to="{ name: 'LoginPage' }"
                      >Iniciar sesión</router-link
                    >
                  </p>
                </div>
                <div class="d-block">
                  <v-btn block color="primary" :loading="loading" @click="login"
                    >Registrar</v-btn
                  >
                </div>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "RegisterPage",
  data: () => ({
    loading: false,
    form: {
      username: "",
      email: "",
      password: "",
      last_name: "",
      first_name: "",
      rol: 2
    }
  }),
  computed: {
    ...mapState({
      currentUser: state => state.session.currentUser
    })
  },
  methods: {
    login() {
      this.loading = true;
      console.log(this.form);
      this.axios
        .post("usuario/registrar", this.form, {
          headers: {
            "Access-Control-Allow-Origin": "*"
          }
        })
        .then(resp => {
          console.log(resp);
        });
      /*
      this.$store
        .dispatch("login", this.form)
        .then(() => {
          let route;
          if (
            ["Residente", "Recepcionista"].includes(this.currentUser.role_name)
          ) {
            route = { name: "GuestPage" };
          } else if (this.currentUser.role_name === "Administrador") {
            route = { name: "NeighborPage" };
          }
          this.$router.push(route);
        })
        .finally(() => (this.loading = false));
      */
    }
  }
};
</script>
<style scoped lang="css">
#login {
  height: 50%;
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  content: "";
  z-index: 0;
}
</style>
