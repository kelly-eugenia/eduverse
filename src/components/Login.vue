<template>
  <div class="container">
    <section>
      <h1>Welcome Back</h1>

      <form ref="form" @submit.prevent="submitLogin" method="POST">
        <p v-error>{{ error }}</p>

        <div class="form-group">
          <label for="username">Username</label>
          <input
            type="text"
            v-model="username"
            class="form-control"
            id="username"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            v-model="password"
            class="form-control"
            id="password"
            required
          />
        </div>

        <input
          class="btn btn-primary"
          type="submit"
          value="Log in"
          id="submit"
        />
      </form>
    </section>
    <p>
      Don't have an account yet?
      <router-link to="/register">Register</router-link>
    </p>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  data() {
    return {
      username: "",
      password: "",
      error: "",
    };
  },
  directives: {
    error: {
      mounted(el) {
        el.style.color = "red";
        el.style.fontSize = "12px";
      },
    },
  },
  computed: {
    ...mapState(["loginError"]),
  },
  methods: {
    ...mapActions(["login"]),
    submitLogin() {
      this.login({
        username: this.username,
        password: this.password,
      }).then((success) => {
        if (success) {
          this.$router.push("/");
        } else {
          this.error = "Invalid username or password.";
        }
      });
    },
  },
};
</script>

<style scoped>
.container {
  margin: 48px auto;
  background-color: #d0e8f3;
  padding: 56px 0;
}

section {
  background-color: white;
  padding: 48px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
  width: 80%;
  margin: 24px auto;
}

h1 {
  text-align: center;
  padding-bottom: 24px;
}

#submit {
  margin: 24px auto;
  width: 100%;
}

p {
  text-align: center;
  font-style: italic;
}

a {
  margin: auto 4px;
}
</style>
