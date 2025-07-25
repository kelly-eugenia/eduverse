<template>
  <div v-if="course">
    <header>
      <section id="title">
        <div class="row">
          <div class="col-12 col-md-4 order-1 order-md-2 text-center">
            <img
              v-bind:src="
                'https://mercury.swin.edu.au/cos30043/s104475686/Project' +
                course.imageURL
              "
              v-bind:alt="course.title"
              class="img-fluid"
            />
          </div>

          <div class="col-12 col-md-8 order-2 order-md-1">
            <h1>{{ course.title }} ({{ course.courseID }})</h1>
            <p>{{ course.description }}</p>
            <div class="row">
              <div class="details col-12 col-md-3">
                <h3>&#x2605; {{ course.ratings }}</h3>
                <p>Rating</p>
              </div>
              <div class="details col-12 col-md-3">
                <h3>{{ course.enrolled }}</h3>
                <p>Learners enrolled</p>
              </div>
              <div class="details col-12 col-md-3">
                <h3>{{ course.duration }} weeks</h3>
                <p>or {{ course.hours }} hours of content</p>
              </div>
            </div>
            <p>
              Instructor: <strong>{{ course.instructor }}</strong>
            </p>
            <small>Last updated {{ course.lastUpdated }}</small>

            <!-- Buttons -->
            <div v-show="role !== 'instructor'">
              <button v-on:click="enroll(course)" class="btn btn-primary">
                Enroll
              </button>
              <button
                @click="like(course)"
                v-html="isLiked(course) ? '&#x2665;' : '&#x2661;'"
                class="btn btn-outline-primary"
                id="likeBtn"
              ></button>
              <span id="msg">{{ message }}</span>
              <small id="likes"
                >{{ course.likes }} people liked this course</small
              >
            </div>
          </div>
        </div>
      </section>
    </header>
    <div class="container">
      <section id="skills">
        <h2>What you'll learn</h2>
        <ul>
          <li v-for="s in course.skills">{{ s }}</li>
        </ul>
      </section>
      <section id="units">
        <h2>Course Content</h2>
        <p>
          {{ Object.keys(course.units).length }} units |
          {{ course.hours }} total hours
        </p>
        <div v-for="(u, index) in course.units" class="unitDetail">
          <h1>{{ index + 1 }}</h1>
          <div>
            <p>
              <strong>{{ u.title }}</strong>
            </p>
            <small>{{ u.materials }} lectures | {{ u.hour }} hours</small>
          </div>
        </div>
      </section>
    </div>
  </div>
  <div v-else>
    <p>Loading course details...</p>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  data() {
    return {
      courseList: [],
      message: "",
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
      "userID",
      "role",
      "enrolledCourses",
      "likedCourses",
    ]),
    course() {
      return this.courseList.find(
        (c) => c.courseID === this.$route.params.courseID
      );
    },
  },
  methods: {
    isLiked(course) {
      if (this.likedCourses.find((c) => c === course.courseID) === undefined) {
        return false;
      } else {
        return true;
      }
    },
    enroll(course) {
      if (!this.user) {
        this.$router.push("/login");
        return;
      }

      if (
        this.enrolledCourses.find((c) => c.courseID === course.courseID) ===
        undefined
      ) {
        axios
          .post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_enroll.php",
            {
              learnerID: this.userID,
              courseID: course.courseID,
            }
          )
          .then((response) => {
            console.log("success response test: ", response.data.message);
            this.message = "Enrolled successfully!";

            this.$store.commit("enrollCourse", response.data.enrollment);

            return axios.post(
              "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/update_courses.php",
              {
                courseID: course.courseID,
                action: "enroll",
              }
            );
          })
          .then((response) => {
            console.log("courses list updated:", response.data.message);
            course.enrolled += 1;
          })
          .catch((error) => {
            console.error("Error during enrollment or update:", error);
            this.message = "Error during enrollment.";
          });
      } else {
        this.message = "You are already enrolled!";
      }
    },
    like(course) {
      if (!this.user) {
        this.$router.push("/login");
        return;
      }

      axios
        .post(
          "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_likes.php",
          {
            learnerID: this.userID,
            courseID: course.courseID,
            action: this.isLiked(course) ? "unlike" : "like",
          }
        )
        .then((response) => {
          console.log("success response test: ", response.data.message);

          return axios.post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/update_courses.php",
            {
              courseID: course.courseID,
              action: this.isLiked(course) ? "unlike" : "like",
            }
          );
        })
        .then((response) => {
          console.log("courses list updated:", response.data.message);
          if (this.isLiked(course)) {
            this.$store.commit("unlikeCourse", course.courseID);
            this.message = "";
            course.likes -= 1;
          } else {
            this.$store.commit("likeCourse", course.courseID);
            this.message = "Liked!";
            course.likes += 1;
          }
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
header {
  background-color: #d0e8f3;
  padding: 24px;
}

#title {
  background-color: white;
  margin: 24px;
  padding: 56px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
}

img {
  padding: 24px;
}

.details p {
  color: grey;
  margin-top: -8px;
}

small {
  display: block;
  margin-top: -12px;
  color: grey;
}

button {
  width: 20%;
  margin: 24px 8px 20px 0;
}

#likeBtn {
  width: auto;
}

#likes {
  font-size: 10px;
  font-style: italic;
}

#msg {
  font-style: italic;
  color: grey;
}

h2 {
  margin-bottom: 24px;
}

ul {
  list-style: none;
  column-count: 2;
}

ul li:before {
  margin-right: 10px;
  margin-left: -10px;
  content: "✓";
  color: rgb(0, 82, 176);
}

.unitDetail {
  border: 1px solid grey;
  border-radius: 8px;
  padding: 24px;
  margin: 18px 0;
}

.unitDetail h1 {
  float: left;
  text-align: right;
  width: 5%;
  margin-top: -8px;
  margin-right: 32px;
  color: rgb(0, 82, 176);
}

.unitDetail div {
  width: 90%;
}
</style>
