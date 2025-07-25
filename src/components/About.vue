<template>
  <div class="container">
    <section id="aboutus">
      <img src="../assets/team.jpeg" alt="aboutus" />
      <h1>About Us</h1>
      <br />
      <h3>
        Welcome to <strong>Eduverse</strong>, your gateway to skill-building and
        career advancement.
      </h3>
      <p>
        Eduverse is an interactive online learning platform that offers a wide
        range of courses in Business, IT, Design, Marketing, and Finance.
        Learners can easily browse, search, and filter courses to find what
        suits their interests, then enroll or like courses to personalize their
        learning journey. Instructors have the ability to add, edit, and manage
        their own courses with ease. The platform also features a dedicated News
        page where users can explore and search through the latest educational
        articles, keeping them informed and inspired.
      </p>
    </section>

    <section id="hello">
      <h1>
        Hello<span v-if="fName || lName">, {{ fName }} {{ lName }}</span
        >!
      </h1>

      <div class="row" id="greetings">
        <div class="col-12 col-md-6">
          <p>What is your name?</p>
          <div class="form-group">
            <label for="fName"><strong>First name:</strong></label>
            <input
              type="text"
              v-model="fName"
              class="form-control"
              id="fName"
              placeholder="Enter your first name..."
            />
          </div>
          <div class="form-group">
            <label for="lName"><strong>Last name:</strong></label>
            <input
              type="text"
              v-model="lName"
              class="form-control"
              id="lName"
              placeholder="Enter your last name..."
            />
          </div>
          <br />
          <p id="radio">Are you a:</p>
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
        <div class="col-12 col-md-6">
          <img
            :src="
              'https://mercury.swin.edu.au/cos30043/s104475686/Project' +
              photoLink
            "
            :alt="role"
          />
        </div>
      </div>

      <router-link to="/register" class="btn btn-primary"
        >Create an account</router-link
      >
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fName: "",
      lName: "",
      role: "learner",
    };
  },
  computed: {
    photoLink: function () {
      return "/assets/" + this.role + ".jpeg";
    },
  },
};
</script>

<style scoped>
h1 {
  text-align: center;
}

section {
  text-align: center;
}

#hello {
  margin: 12px;
  margin-top: 24px;
  padding: 48px;
  box-shadow: 1px 4px 8px #a5b6cf88;
  border-radius: 8px;
  background-color: white;
}

#hello a {
  margin-top: 24px;
}

#greetings {
  text-align: left;
}

input::placeholder {
  font-size: small;
}

p#radio {
  display: inline;
  margin-right: 24px;
}

.form-check-label {
  display: inline;
}

img {
  width: 100%;
  display: block;
  padding: 24px;
}
</style>
