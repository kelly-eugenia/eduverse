<template>
  <div class="container">
    <div v-if="this.$store.getters.role === 'instructor'">
      <div class="row">
        <div class="col-12">
          <h1>Add New Course</h1>

          <form
            ref="form"
            @submit.prevent="validateForm"
            method="post"
            novalidate
          >
            <p v-if="errors.general" v-error>{{ errors.general }}</p>

            <!-- Course Details -->
            <fieldset>
              <legend>Course Details</legend>

              <div class="form-group">
                <label for="title">Title<span v-error>*</span></label>
                <input
                  type="text"
                  v-model="title"
                  class="form-control"
                  name="title"
                  required
                />
                <span v-if="errors.title" class="form-text" v-error>{{
                  errors.title
                }}</span>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 col-md-6">
                  <label for="category">Category<span v-error>*</span></label>
                  <select
                    class="form-select"
                    v-model="category"
                    id="category"
                    required
                  >
                    <option value="">(Select category)</option>
                    <option value="Business">Business</option>
                    <option value="IT & Computer Science">
                      IT & Computer Science
                    </option>
                    <option value="Design">Design</option>
                    <option value="Finance">Finance</option>
                    <option value="Marketing">Marketing</option>
                  </select>
                  <span v-if="errors.category" class="form-text" v-error>{{
                    errors.category
                  }}</span>
                </div>

                <div class="col-sm-12 col-md-6">
                  <label for="difficulty">Level<span v-error>*</span></label>
                  <select
                    class="form-select"
                    v-model="difficulty"
                    id="difficulty"
                    required
                  >
                    <option value="">(Select level)</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                  </select>
                  <span v-if="errors.difficulty" class="form-text" v-error>{{
                    errors.difficulty
                  }}</span>
                </div>
              </div>

              <div class="form-group">
                <label for="description"
                  >Description<span v-error>*</span></label
                >
                <textarea
                  class="form-control"
                  v-model="description"
                  id="description"
                  rows="3"
                  required
                ></textarea>
                <span v-if="errors.description" class="form-text" v-error>{{
                  errors.description
                }}</span>
              </div>

              <div class="form-group">
                <label for="skills">Skills<span v-error>*</span></label>
                <textarea
                  class="form-control"
                  v-model="skills"
                  id="skills"
                  rows="2"
                  required
                ></textarea>
                <small id="skillsHelp" class="form-text text-muted">
                  Minimum 2 skills. Seperated by comma, e.g. Data visualisation,
                  Decision making </small
                ><br />
                <span v-if="errors.skills" class="form-text" v-error>{{
                  errors.skills
                }}</span>
              </div>
            </fieldset>
            <br />

            <!-- Units Details -->
            <fieldset>
              <legend>Course Content</legend>
              <div class="form-group row">
                <div class="col-6">
                  <label for="unitnum"
                    >Number of units<span v-error>*</span></label
                  >
                  <input
                    type="number"
                    v-model="unitnum"
                    class="form-control"
                    id="unitnum"
                    min="2"
                    required
                  />
                  <span v-if="errors.unitnum" class="form-text" v-error>{{
                    errors.unitnum
                  }}</span>
                </div>
              </div>

              <div v-for="i in unitnum" :key="i" class="unitDetails">
                <h5>Unit {{ i }}</h5>
                <div class="form-group">
                  <label v-bind:for="'title_' + i"
                    >Unit title<span v-error>*</span></label
                  >
                  <input
                    v-if="units[i - 1]"
                    type="text"
                    v-model="units[i - 1].title"
                    class="form-control"
                    v-bind:id="'title_' + i"
                    required
                  />
                  <span
                    v-if="
                      errors.units &&
                      errors.units[i - 1] &&
                      errors.units[i - 1].title
                    "
                    class="form-text"
                    v-error
                    >{{ errors.units[i - 1].title }}</span
                  >
                </div>
                <div class="form-group row">
                  <div class="col-6">
                    <label v-bind:for="'materials_' + i"
                      >How many lectures?<span v-error>*</span></label
                    >
                    <input
                      v-if="units[i - 1]"
                      type="number"
                      v-model="units[i - 1].materials"
                      class="form-control"
                      v-bind:id="'materials_' + i"
                      min="1"
                      required
                    />
                    <span
                      v-if="
                        errors.units &&
                        errors.units[i - 1] &&
                        errors.units[i - 1].materials
                      "
                      class="form-text"
                      v-error
                      >{{ errors.units[i - 1].materials }}</span
                    >
                  </div>

                  <div class="col-6">
                    <label v-bind:for="'hour_' + i"
                      >How many hours?<span v-error>*</span></label
                    >
                    <input
                      v-if="units[i - 1]"
                      type="number"
                      v-model="units[i - 1].hour"
                      class="form-control"
                      v-bind:id="'hour_' + i"
                      min="1"
                      required
                    />
                    <span
                      v-if="
                        errors.units &&
                        errors.units[i - 1] &&
                        errors.units[i - 1].hour
                      "
                      class="form-text"
                      v-error
                      >{{ errors.units[i - 1].hour }}</span
                    >
                  </div>
                </div>
              </div>
            </fieldset>

            <!-- Buttons -->
            <input
              class="btn btn-primary"
              type="submit"
              value="Submit"
              id="submitBtn"
            />
          </form>
        </div>
      </div>
    </div>
    <div v-else>
      <p>You are not an instructor yet. Please log in.</p>
      <router-link to="/login">Log in</router-link>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      title: "",
      category: "",
      difficulty: "",
      description: "",
      skills: "",
      skillList: [""],
      unitnum: 2,
      units: [
        { title: "", materials: null, hour: null },
        { title: "", materials: null, hour: null },
      ],
      errors: {
        general: "",
        title: "",
        category: "",
        difficulty: "",
        description: "",
        skills: "",
        unitnum: "",
        units: [
          { title: "", materials: "", hour: "" },
          { title: "", materials: "", hour: "" },
        ],
      },
      isValid: true,
    };
  },
  directives: {
    error: {
      mounted(el) {
        el.style.color = "red";
        el.style.fontSize = "12px";
      },
      updated(el) {
        el.style.color = "red";
        el.style.fontSize = "12px";
      },
    },
  },
  watch: {
    unitnum(newVal) {
      if (newVal > this.units.length) {
        for (let i = this.units.length; i < newVal; i++) {
          this.units.push({ title: "", materials: null, hour: null });
          this.errors.units.push({ title: "", materials: "", hour: "" });
        }
      } else {
        this.units.length = newVal;
        this.errors.units.length = newVal;
      }
    },
  },
  methods: {
    validateForm() {
      (this.errors = {
        general: "",
        title: "",
        category: "",
        difficulty: "",
        description: "",
        skills: "",
        unitnum: "",
        units: [],
      }),
        (this.isValid = true);

      if (!this.title || this.title.length < 2 || this.title.length > 40) {
        this.errors.title = "Title must be 2–40 characters.";
        this.isValid = false;
      }

      if (!this.category) {
        this.errors.category = "Please select a category.";
        this.isValid = false;
      }

      if (!this.difficulty) {
        this.errors.difficulty = "Please select a level.";
        this.isValid = false;
      }

      if (
        !this.description ||
        this.description.length < 10 ||
        this.description.length > 200
      ) {
        this.errors.description = "Description must be 10–200 characters.";
        this.isValid = false;
      }

      this.skillList = this.skills
        .split(",")
        .map((s) => s.trim())
        .filter((s) => s);
      if (this.skillList.length < 2) {
        this.errors.skills =
          "Please enter at least 2 skills, separated by commas.";
        this.isValid = false;
      }

      if (!this.unitnum || this.unitnum < 2) {
        this.errors.unitnum = "Please enter at least 2 units.";
        this.isValid = false;
      }

      this.errors.units = Array.from({ length: this.unitnum }, () => ({
        title: "",
        materials: "",
        hour: "",
      }));
      for (let i = 0; i < this.unitnum; i++) {
        const unit = this.units[i];
        const unitErrors = {
          title: "",
          materials: "",
          hour: "",
        };

        if (!unit.title || unit.title.length < 2 || unit.title.length > 40) {
          unitErrors.title = "Title must be 2–40 characters.";
          this.isValid = false;
        }

        if (!Number.isInteger(unit.materials) || unit.materials < 1) {
          unitErrors.materials = "Must be a minimum of 1 lecture per unit.";
          this.isValid = false;
        }

        if (!unit.hour || unit.hour < 1) {
          unitErrors.hour = "Must be a minimum of 1 hour per unit.";
          this.isValid = false;
        }

        this.errors.units[i] = unitErrors;
      }

      // Submit if no errors
      if (this.isValid) {
        axios
          .post(
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/add_course.php",
            {
              title: this.title,
              category: this.category,
              instructor: this.$store.getters.fullName,
              difficulty: this.difficulty,
              skills: this.skillList,
              description: this.description,
              units: this.units,
            }
          )
          .then((response) => {
            console.log("course list updated: ", response.data.message);

            return axios.post(
              "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_addcourse.php",
              {
                instructorID: this.$store.getters.userID,
                courseID: response.data.courseID,
                date: response.data.date,
              }
            );
          })
          .then((response) => {
            console.log("database updated:", response.data.message);

            this.$store.commit("addCourse", response.data.course.courseID);

            alert("Successfully added course!");
            this.$router.push("/courses/" + response.data.course.courseID);
          })
          .catch((error) => {
            console.error("Error during adding course:", error);
            this.errors.general = "An unexpected error occurred.";
          });
      }
    },
  },
};
</script>

<style scoped>
fieldset {
  width: 100%;
  border-bottom: 1px solid #ccc;
  padding-bottom: 32px;
}
label {
  margin-top: 12px;
}
.unitDetails {
  margin: 24px 0;
  border: 1px solid grey;
  border-radius: 8px;
  padding: 24px;
}
#submitBtn {
  margin: 24px 0;
  width: 30%;
}
</style>
