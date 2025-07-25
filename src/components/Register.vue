<template>
  <div class="container">
    <section>
      <h1>Join Us</h1>
      <div id="generate">
        <button
          type="button"
          @click="generateUser()"
          class="btn btn-outline-secondary"
        >
          Generate Sample User
        </button>
      </div>

      <form ref="form" @submit.prevent="validateForm" method="post" novalidate>
        <div v-if="errors.general">
          <p v-if="errors.general" v-error>{{ errors.general }}</p>
        </div>

        <!-- Personal Information -->
        <fieldset>
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label for="firstName">First Name<span v-error>*</span></label>
              <input
                type="text"
                v-model="firstName"
                class="form-control"
                name="firstName"
                pattern="[a-zA-Z ]{2,20}"
                required
              />
              <span v-if="errors.firstName" class="form-text" v-error>{{
                errors.firstName
              }}</span>
            </div>
            <div class="form-group col-12 col-md-6">
              <label for="lastName">Last Name<span v-error>*</span></label>
              <input
                type="text"
                v-model="lastName"
                class="form-control"
                name="lastName"
                pattern="[a-zA-Z ]{2,20}"
                required
              />
              <span v-if="errors.lastName" v-error>{{ errors.lastName }}</span>
            </div>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth<span v-error>*</span></label>
            <input
              type="date"
              v-model="dob"
              class="form-control"
              name="dob"
              required
            />
            <span v-if="errors.dob" v-error>{{ errors.dob }}</span>
          </div>

          <div class="form-group">
            <label for="username">Username<span v-error>*</span></label>
            <input
              type="text"
              v-model="username"
              class="form-control"
              name="username"
              pattern="{3,}"
              title="Minimum 3 characters."
              required
            />
            <span v-if="errors.username" v-error>{{ errors.username }}</span>
          </div>

          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label for="password">Password<span v-error>*</span></label>
              <input
                type="password"
                v-model="password"
                class="form-control"
                name="password"
                pattern="(?=.*$%^&*).{8,}"
                required
              />
              <span v-if="errors.password" v-error>{{ errors.password }}</span>
            </div>

            <div class="form-group col-12 col-md-6">
              <label for="confpassword"
                >Confirm Password<span v-error>*</span></label
              >
              <input
                type="password"
                v-model="confpassword"
                class="form-control"
                name="confpassword"
                pattern="(?=.*$%^&*).{8,}"
                required
              />
              <span v-if="errors.confpassword" v-error>{{
                errors.confpassword
              }}</span>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email<span v-error>*</span></label>
            <input
              type="email"
              v-model="email"
              class="form-control"
              name="email"
              pattern="[a-zA-Z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
              placeholder="e.g. user@example.com"
              required
            />
            <span v-if="errors.email" v-error>{{ errors.email }}</span>
          </div>
        </fieldset>

        <div id="role">
          <p class="radio d-block d-md-inline">Are you a:</p>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              v-model="role"
              id="learner"
              value="learner"
              checked
            />
            <label class="form-check-label radio" for="learner">Learner</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              v-model="role"
              id="instructor"
              value="instructor"
            />
            <label class="form-check-label radio" for="instructor"
              >Instructor</label
            >
          </div>
        </div>

        <input
          class="btn btn-primary"
          type="submit"
          value="Submit"
          id="submit"
        />
      </form>
    </section>
    <p id="acc">
      Already have an account?
      <router-link to="/login">Log in</router-link>
    </p>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      firstName: "",
      lastName: "",
      dob: "",
      username: "",
      password: "",
      confpassword: "",
      email: "",
      role: "learner",
      errors: {},
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
  methods: {
    validateForm() {
      this.errors = {};

      if (!/^[a-zA-Z ]{2,20}$/.test(this.firstName)) {
        this.errors.firstName = "First name must be 2–20 letters.";
      }

      if (!/^[a-zA-Z ]{2,20}$/.test(this.lastName)) {
        this.errors.lastName = "Last name must be 2–20 letters.";
      }

      const age = this.calculateAge(this.dob);
      if (!this.dob || age < 16) {
        this.errors.dob = "You must be at least 16 years old.";
      }

      if (!this.username || this.username.length < 3) {
        this.errors.username = "Username must be at least 3 characters.";
      }

      if (!/(?=.*[$%^&*]).{8,}/.test(this.password)) {
        this.errors.password =
          "Password must be 8+ chars and include special char ($%^&*).";
      }

      if (this.confpassword !== this.password) {
        this.errors.confpassword = "Passwords must match.";
      }

      if (!/^[a-zA-Z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/.test(this.email)) {
        this.errors.email = "Invalid email format.";
      }

      // Submit if no errors
      if (Object.keys(this.errors).length === 0) {
        axios
          .post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_register.php",
            {
              firstName: this.firstName,
              lastName: this.lastName,
              dob: this.dob,
              username: this.username,
              password: this.password,
              email: this.email,
              role: this.role,
            }
          )
          .then((response) => {
            alert("Successfully registered! Please log in.");
            this.$router.push("/login");
          })
          .catch((error) => {
            console.error("Error during register:", error);
            this.errors.general = "An unexpected error occurred.";
          });
      }
    },
    calculateAge(dob) {
      const birth = new Date(dob);
      const today = new Date();
      let age = today.getFullYear() - birth.getFullYear();
      const m = today.getMonth() - birth.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
        age--;
      }
      return age;
    },
    generateUser() {
      fetch("https://randomuser.me/api/")
        .then((res) => res.json())
        .then((data) => {
          const user = data.results[0];
          this.firstName = user.name.first;
          this.lastName = user.name.last;
          this.dob = "2000-01-01";
          this.username = user.login.username;
          this.password = "pass123$";
          this.confpassword = "pass123$";
          this.email = user.email;
          this.role = "learner";
        })
        .catch((error) => {
          console.error("Failed to fetch random user:", error);
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

h1 {
  text-align: center;
  padding-bottom: 24px;
}

section {
  background-color: white;
  padding: 48px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
  width: 80%;
  margin: 24px auto;
}

div#role {
  margin-top: 24px;
}

.radio {
  text-align: left;
  display: inline;
  margin-right: 24px;
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

#generate {
  text-align: right;
  margin-top: -8px;
  margin-bottom: 24px;
}
#generate button {
  width: auto;
}
</style>
