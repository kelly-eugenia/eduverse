<template>
  <div class="container">
    <div v-if="role === 'instructor'">
      <section id="profile">
        <small>INSTRUCTOR PROFILE</small>
        <h1>{{ fullName }}</h1>
        <p>{{ username }} | {{ email }}</p>
        <button class="btn btn-danger" @click="logout">Logout</button>
      </section>
      <section id="courses">
        <h1>Your Courses</h1>
        <div v-for="c in addedCourses" :key="course(c)" class="courselist">
          <h4>
            <router-link :to="{ path: '/courses/' + c }">
              {{ course(c).title }} ({{ course(c).courseID }})
            </router-link>
          </h4>
          <small>
            {{ course(c).category }} | {{ course(c).hours }} total hours
          </small>
          <p>
            <strong>{{ course(c).enrolled }}</strong> learners enrolled
          </p>
          <p>
            <strong>&#x2605; {{ course(c).ratings }}</strong>
          </p>
          <router-link
            :to="{ path: '/editcourse/' + course(c).courseID }"
            class="btn btn-outline-primary"
          >
            Edit
          </router-link>
          <button @click="openModal(c)" class="btn btn-outline-danger">
            Delete
          </button>
        </div>
        <DeleteCourse v-if="isModalOpen" :courseID="id" @close="closeModal">
        </DeleteCourse>
        <div v-show="addedCourses.length === 0">No courses found.</div>
        <br />
        <router-link :to="{ path: '/addcourse' }" class="btn btn-primary">
          Add course
        </router-link>
      </section>
    </div>
    <div v-else>
      <p>You are not an instructor yet. Please log in.</p>
      <router-link to="/login">Log in</router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import DeleteCourse from "../components/DeleteCourse.vue";

export default {
  data() {
    return {
      courseList: [],
      isModalOpen: false,
      id: "",
    };
  },
  mounted() {
    fetch(
      "https://mercury.swin.edu.au/cos30043/s104475686/Project/courses.json"
    )
      .then((res) => res.json())
      .then((data) => {
        this.courseList = data;
      })
      .catch((error) => {
        console.error("Failed to fetch courses.json:", error);
      });
  },
  components: { DeleteCourse },
  computed: {
    ...mapGetters([
      "user",
      "role",
      "fullName",
      "email",
      "username",
      "addedCourses",
    ]),
  },
  methods: {
    course(id) {
      return this.courseList.find((c) => c.courseID === id);
    },
    logout() {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    openModal(courseID) {
      this.isModalOpen = true;
      this.id = courseID;
    },
    closeModal() {
      this.isModalOpen = false;
    },
  },
};
</script>

<style scoped>
section {
  margin: 24px;
  padding: 56px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
  background-color: white;
}

#profile {
  text-align: center;
}
h1 {
  padding: 0;
}

small {
  font-weight: 500;
  color: grey;
}

.courselist {
  padding: 24px 0;
  border-bottom: 1px solid #ccc;
}

.courselist p {
  margin: auto;
}

.courselist small {
  display: block;
  margin-bottom: 8px;
}

.courselist .btn {
  margin: 18px 8px 0 0;
}
</style>
