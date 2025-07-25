<template>
  <div class="container">
    <div v-if="role === 'learner'">
      <section id="profile">
        <small>LEARNER PROFILE</small>
        <h1>{{ fullName }}</h1>
        <p>{{ username }} | {{ email }}</p>
        <button class="btn btn-danger" @click="logout">Logout</button>
      </section>
      <section id="enrolled">
        <h1>Enrolled Courses</h1>
        <div v-for="c in enrolledCourses" :key="c.courseID" class="courselist">
          <h4>
            <router-link :to="{ path: '/courses/' + c.courseID }">
              {{ course(c.courseID).title }}
            </router-link>
          </h4>

          <p>Enrolled from {{ c.enrolledDate }}</p>
          <p v-if="c.progress == 100">Completed on {{ c.completedDate }}</p>
          <p v-else>
            Progress: <strong> {{ c.progress }}% </strong>
          </p>
        </div>
        <div v-show="enrolledCourses.length === 0">No courses found.</div>
        <br />
        <router-link :to="{ path: '/courses' }" class="btn btn-primary">
          Explore more courses
        </router-link>
      </section>
      <section id="liked">
        <h1>Liked Courses</h1>
        <div v-for="c in likedCourses" :key="course(c)" class="courselist">
          <h4>
            <router-link :to="{ path: '/courses/' + c }">
              {{ course(c).title }}
            </router-link>
          </h4>
          <small>
            {{ course(c).instructor }} | {{ course(c).category }} |
            {{ course(c).hours }} total hours
          </small>
          <button
            @click="unlike(course(c))"
            class="btn btn-outline-danger btn-sm"
            id="unlikeBtn"
          >
            Unlike
          </button>
        </div>
        <div v-show="likedCourses.length === 0">No courses found.</div>
      </section>
    </div>
    <div v-else>
      <p>You are not a learner yet. Please log in.</p>
      <router-link to="/login">Log in</router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  data() {
    return {
      courseList: [],
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
  computed: {
    ...mapGetters([
      "user",
      "role",
      "fullName",
      "email",
      "username",
      "enrolledCourses",
      "likedCourses",
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
    unlike(course) {
      axios
        .post(
          "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_likes.php",
          {
            learnerID: this.userID,
            courseID: course.courseID,
            action: "unlike",
          }
        )
        .then((response) => {
          console.log("success response test: ", response.data.message);

          return axios.post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/update_courses.php",
            {
              courseID: course.courseID,
              action: "unlike",
            }
          );
        })
        .then((response) => {
          console.log("courses list updated:", response.data.message);
          this.$store.commit("unlikeCourse", course.courseID);
          course.likes -= 1;
        })
        .catch((error) => {
          console.error("Error during like or update:", error);
          this.message = "Error updating likes.";
        });
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
</style>
