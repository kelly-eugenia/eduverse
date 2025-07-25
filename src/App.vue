<template>
  <div id="app" class="d-flex flex-column min-vh-100">
    <nav>
      <div id="companyname">
        <h2>Eduverse</h2>
      </div>
      <div id="menulinks">
        <router-link to="/">Home</router-link>
        <router-link to="/courses">Courses</router-link>
        <router-link to="/news">News</router-link>
        <router-link to="/about">About</router-link>
      </div>
      <div id="account">
        <router-link v-if="!user" to="/login" class="btn btn-outline-primary"
          >Log in</router-link
        >
        <router-link
          v-else-if="role === 'learner'"
          to="/account/learner"
          class="btn btn-outline-primary"
          >&#x263A; {{ fullName }}</router-link
        >
        <router-link
          v-else-if="role === 'instructor'"
          to="/account/instructor"
          class="btn btn-outline-primary"
          >&#x263A; {{ fullName }}</router-link
        >
      </div>
    </nav>

    <main class="flex-fill">
      <router-view />
    </main>

    <footer>
      <small>2025 by Kelly Sutopo</small>
    </footer>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["user", "role", "fullName"]),
  },
  mounted() {
    this.$router.push("/");
  },
  created() {
    const savedUser = localStorage.getItem("user");
    if (savedUser) {
      this.$store.commit("setUser", JSON.parse(savedUser));
    }
  },
  methods: {
    logout() {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
};
</script>

<style scoped>
main {
  background-color: rgb(247, 247, 247);
}

nav {
  background-color: rgb(0, 82, 176);
  display: flex;
  align-items: center; /* Vertically center content */
  justify-content: space-between; /* Distribute three sections */
  flex-wrap: wrap;
  padding: 8px 16px;
  min-height: 64px;
}

#companyname {
  color: white;
  font-size: 28px;
  font-weight: 600;
  flex: 1;
  margin: auto 16px;
}

#menulinks {
  display: flex;
  justify-content: center;
  flex: 2;
  gap: 8px;
}

#account {
  flex: 1;
  display: flex;
  justify-content: flex-end;
  margin: auto 16px;
}

#account a {
  border: 2px solid white;
}

nav a {
  display: inline-block;
  padding: 6px 12px;
  margin: auto 8px;
  text-decoration: none;
  color: white;
}

nav a:hover {
  text-decoration: underline;
}

footer {
  padding: 12px;
  text-align: center;
  background-color: rgb(0, 82, 176);
  left: 0;
  bottom: 0;
  width: 100%;
}

footer small {
  color: white;
}

@media screen and (max-width: 768px) {
  nav {
    flex-direction: column;
    align-items: stretch;
    text-align: center;
  }

  #companyname,
  #menulinks,
  #account {
    flex: unset;
    margin: 8px 0;
  }

  #account {
    justify-content: center;
  }
}
</style>
