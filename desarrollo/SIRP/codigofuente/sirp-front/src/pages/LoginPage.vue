<template>
  <v-app id="login" class="primary">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4 lg3>
            <v-card class="elevation-1 pa-3">
              <v-card-text>
                <div class="layout column align-center">
                  <img
                    src="../assets/neighborhood.png"
                    alt="Vecindad"
                    width="64"
                    height="64"
                  />
                  <h1 class="flex my-4 primary--text">Vecindad</h1>
                </div>
                <v-form>
                  <v-text-field
                    v-model="form.email"
                    append-icon="person"
                    name="login"
                    label="Correo"
                    type="email"
                  ></v-text-field>
                  <v-text-field
                    id="password"
                    v-model="form.password"
                    append-icon="lock"
                    name="password"
                    label="Contraseña"
                    type="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn block color="primary" :loading="loading" @click="login"
                  >Login</v-btn
                >
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
  name: "LoginPage",
  data: () => ({
    loading: false,
    form: {
      email: "antonioadolvv@gmail.com",
      password: "12345678",
      remember_me: true
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
