<template>
  <div class="container">
    <div v-if="this.$store.getters.role === 'instructor'">
      <div class="row">
        <div class="col-12">
          <h1>Edit Course {{ course.courseID }}</h1>

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
                  <input
                    type="text"
                    v-model="category"
                    class="form-control readonly"
                    name="category"
                    readonly
                  />
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
                <small class="form-text text-muted">
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
                  <label for="unitnum">Number of units</label>
                  <input
                    type="number"
                    v-model="unitnum"
                    class="form-control readonly"
                    id="unitnum"
                    min="2"
                    readonly
                  />
                  <span v-if="errors.unitnum" class="form-text" v-error>{{
                    errors.unitnum
                  }}</span>
                </div>
              </div>

              <div v-for="i in unitnum" :key="i" class="unitDetails">
                <div id="unitnumber">
                  <h5>Unit {{ i }}</h5>
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="removeUnit(i)"
                  >
                    Remove
                  </button>
                </div>

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

              <button
                type="button"
                class="btn btn-outline-primary"
                @click="addUnit"
              >
                Add Unit
              </button>
            </fieldset>

            <!-- Buttons -->
            <router-link
              :to="{ path: '/account/instructor' }"
              class="btn btn-secondary"
            >
              Cancel
            </router-link>
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
      courseList: [],
      course: {},

      title: "",
      category: "",
      difficulty: "",
      description: "",
      skills: "",
      unitnum: null,
      units: [],

      errors: {
        general: "",
        title: "",
        difficulty: "",
        description: "",
        skills: "",
        unitnum: "",
        units: [],
      },
      isValid: true,
    };
  },
  mounted() {
    fetch(
      "https://mercury.swin.edu.au/cos30043/s104475686/Project/courses.json"
    )
      .then((res) => res.json())
      .then((data) => {
        this.courseList = data;
        this.course = this.courseList.find(
          (c) => c.courseID === this.$route.params.courseID
        );

        // Populate form fields after fetch
        this.title = this.course.title;
        this.category = this.course.category;
        this.difficulty = this.course.difficulty;
        this.description = this.course.description;
        this.skills = this.course.skills.join(", ");
        this.unitnum = this.course.units.length;
        this.units = JSON.parse(JSON.stringify(this.course.units));
      })
      .catch((error) => {
        console.error("Failed to fetch course: ", error);
      });
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
  methods: {
    addUnit() {
      this.units.push({ title: "", materials: null, hour: null });
      this.errors.units.push({ title: "", materials: "", hour: "" });
      this.unitnum = this.units.length;
    },
    removeUnit(index) {
      this.units.splice(index - 1, 1);
      this.errors.units.splice(index - 1, 1);
      this.unitnum = this.units.length;
    },
    validateForm() {
      (this.errors = {
        general: "",
        title: "",
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

      if (this.unitnum < 2) {
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
            "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/edit_course.php",
            {
              courseID: this.course.courseID,
              title: this.title,
              difficulty: this.difficulty,
              skills: this.skillList,
              description: this.description,
              units: this.units,
            }
          )
          .then((response) => {
            console.log("course list updated:", response.data.message);

            alert("Successfully edited course!");
            this.$router.push("/courses/" + this.course.courseID);
          })
          .catch((error) => {
            console.error("Error during editing course:", error);
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
.readonly {
  background-color: #ececec;
}
.unitDetails {
  margin: 24px 0;
  border: 1px solid grey;
  border-radius: 8px;
  padding: 24px;
}
#unitnumber h5,
#unitnumber button {
  display: inline;
  margin-right: 24px;
}
#submitBtn {
  margin: 24px 0 24px 8px;
}
</style>
