<template>
  <div id="modal-overlay">
    <div id="modal" v-if="course">
      <h3>
        Are you sure you want to delete
        <strong>"{{ course.title }} ({{ course.courseID }})</strong>"?
      </h3>
      <button class="btn btn-secondary" @click="closeModal">Cancel</button>
      <button class="btn btn-danger" @click="deleteCourse(course)">
        Delete
      </button>
      <p v-show="message">{{ message }}</p>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  data() {
    return {
      course: {},
      message: "",
    };
  },
  mounted() {
    fetch(
      "https://mercury.swin.edu.au/cos30043/s104475686/Project/courses.json"
    )
      .then((res) => res.json())
      .then((data) => {
        this.course = data.find((c) => c.courseID === this.courseID);
      })
      .catch((error) => {
        console.error("Failed to fetch course: ", error);
      });
  },
  props: {
    courseID: "",
  },
  computed: {
    ...mapGetters(["user", "userID"]),
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    deleteCourse(course) {
      axios
        .post(
          "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_deletecourse.php",
          {
            instructorID: this.userID,
            courseID: course.courseID,
          }
        )
        .then((response) => {
          console.log("success response test: ", response.data.message);

          return axios.post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/delete_course.php",
            {
              courseID: course.courseID,
            }
          );
        })
        .then((response) => {
          console.log("courses list updated:", response.data.message);
          this.$store.commit("deleteCourse", course.courseID);

          alert("Successfully deleted course!");
          this.$emit("close");
        })
        .catch((error) => {
          console.error("Error during delete or update:", error);
          this.message = "Error during deleting course.";
        });
    },
  },
};
</script>

<style scoped>
#modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 55, 74, 0.292);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

#modal {
  background-color: white;
  padding: 48px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
  text-align: center;
}

button {
  margin: 12px 4px;
  width: 30%;
}
</style>
