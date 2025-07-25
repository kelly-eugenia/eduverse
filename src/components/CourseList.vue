<template>
  <div class="container">
    <h1>Our Courses</h1>

    <div class="form-group" id="searchBar">
      <input
        type="text"
        v-model="search"
        class="form-control"
        id="search"
        placeholder="Search for a course or topic..."
      />
    </div>

    <div class="row" id="content">
      <div class="col-12 col-md-3" id="filters">
        <h3>Filters</h3>

        <!-- Category -->
        <div class="filter">
          <p><strong>Category</strong></p>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedCategories"
              value="Business"
              id="Business"
            />
            <label class="form-check-label" for="Business"> Business </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedCategories"
              value="IT & Computer Science"
              id="IT"
            />
            <label class="form-check-label" for="IT">
              IT & Computer Science
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedCategories"
              value="Design"
              id="Design"
            />
            <label class="form-check-label" for="Design"> Design </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedCategories"
              value="Finance"
              id="Finance"
            />
            <label class="form-check-label" for="Finance"> Finance </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedCategories"
              value="Marketing"
              id="Marketing"
            />
            <label class="form-check-label" for="Marketing"> Marketing </label>
          </div>
        </div>

        <!-- Ratings -->
        <div class="filter">
          <p><strong>Ratings</strong></p>
          <div class="form-check">
            <input
              class="form-check-input"
              type="radio"
              v-model="selectedRatings"
              value="4.5"
              id="4.5+"
            />
            <label class="form-check-label" for="4.5+"
              >&#x2605; 4.5 & up
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="radio"
              v-model="selectedRatings"
              value="4"
              id="4+"
            />
            <label class="form-check-label" for="4+">&#x2605; 4.0 & up </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="radio"
              v-model="selectedRatings"
              value="3.5"
              id="3.5+"
            />
            <label class="form-check-label" for="3.5+"
              >&#x2605; 3.5 & up
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="radio"
              v-model="selectedRatings"
              value="3"
              id="3.0+"
            />
            <label class="form-check-label" for="3.0+"
              >&#x2605; 3.0 & up
            </label>
          </div>
        </div>

        <!-- Duration -->
        <div class="filter">
          <p><strong>Duration</strong></p>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedDurations"
              value="1-30h"
              id="1-30h"
            />
            <label class="form-check-label" for="1-30h"> 1-30 Hours </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedDurations"
              value="31-60h"
              id="31-60h"
            />
            <label class="form-check-label" for="31-60h"> 31-60 Hours </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedDurations"
              value="61-90h"
              id="61-90h"
            />
            <label class="form-check-label" for="61-90h"> 61-90 Hours </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedDurations"
              value="91+h"
              id="91+h"
            />
            <label class="form-check-label" for="91+h"> 91+ Hours </label>
          </div>
        </div>

        <!-- Level -->
        <div class="filter">
          <p><strong>Level</strong></p>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedLevels"
              value="Beginner"
              id="Beginner"
            />
            <label class="form-check-label" for="Beginner"> Beginner </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedLevels"
              value="Intermediate"
              id="Intermediate"
            />
            <label class="form-check-label" for="Intermediate">
              Intermediate
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="selectedLevels"
              value="Advanced"
              id="Advanced"
            />
            <label class="form-check-label" for="Advanced"> Advanced </label>
          </div>
        </div>
        <div class="row">
          <button class="btn btn-secondary" @click="clearFilters">
            Clear filters
          </button>
        </div>
      </div>
      <div class="col-12 col-md-9" id="list">
        <div
          v-for="course in paginatedCourses"
          :key="course.courseID"
          class="course"
        >
          <router-link :to="{ path: '/courses/' + course.courseID }">
            {{ course.title }}
          </router-link>
          <small>
            {{ course.instructor }} | {{ course.category }} |
            {{ course.hours }} total hours
          </small>
          <p>
            <strong>&#x2605; {{ course.ratings }}</strong>
          </p>
        </div>
        <div v-show="paginatedCourses.length === 0">
          <p>No courses found.</p>
        </div>
        <Paginate
          :page-count="getPageCount"
          :page-range="3"
          :margin-pages="1"
          :click-handler="clickCallback"
          :prev-text="'Prev'"
          :next-text="'Next'"
          :container-class="'pagination'"
          :page-class="'page-item'"
          :active-class="'currentPage'"
        >
        </Paginate>
      </div>
    </div>
  </div>
  <router-view />
</template>

<script>
import Paginate from "vuejs-paginate-next";

export default {
  data() {
    return {
      search: "",
      selectedCategories: [],
      selectedLevels: [],
      selectedDurations: [],
      selectedRatings: null,
      perPage: 4,
      currentPage: 1,
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
  components: {
    Paginate,
  },
  computed: {
    filteredCourses() {
      this.currentPage = 1;
      return this.courseList.filter((course) => {
        const searchText = this.search.toLowerCase();
        const matchesSearch =
          course.courseID.toString().includes(searchText) ||
          course.title.toLowerCase().includes(searchText) ||
          course.instructor.toLowerCase().includes(searchText) ||
          course.category.toLowerCase().includes(searchText);

        const matchesCategory =
          this.selectedCategories.length === 0 ||
          this.selectedCategories.includes(course.category);

        const matchesRatings =
          !this.selectedRatings ||
          course.ratings >= parseFloat(this.selectedRatings);

        const matchesLevel =
          this.selectedLevels.length === 0 ||
          this.selectedLevels.includes(course.difficulty);

        const duration = this.getDuration(course.hours);
        const matchesDuration =
          this.selectedDurations.length === 0 ||
          this.selectedDurations.includes(duration);

        return (
          matchesSearch &&
          matchesCategory &&
          matchesRatings &&
          matchesLevel &&
          matchesDuration
        );
      });
    },

    paginatedCourses() {
      let current = this.currentPage * this.perPage;
      let start = current - this.perPage;
      return this.filteredCourses.slice(start, current);
    },

    getPageCount: function () {
      return Math.ceil(this.filteredCourses.length / this.perPage);
    },
  },
  methods: {
    clickCallback: function (pageNum) {
      this.currentPage = Number(pageNum);
    },
    getDuration(hours) {
      if (hours <= 30) {
        return "1-30h";
      } else if (31 <= hours && hours <= 60) {
        return "31-60h";
      } else if (61 <= hours && hours <= 90) {
        return "61-90h";
      } else {
        return "91+h";
      }
    },
    clearFilters() {
      this.selectedCategories.length = 0;
      this.selectedRatings = null;
      this.selectedLevels.length = 0;
      this.selectedDurations.length = 0;
    },
  },
};
</script>

<style scoped>
h1 {
  text-align: center;
}

#searchBar {
  width: 60%;
  margin: 24px auto;
  text-align: center;
  color: grey;
  font-size: smaller;
}

#searchBar input::placeholder {
  font-size: small;
}

#content {
  margin-top: 56px;
}

#filters {
  padding: 12px;
  border-right: 1px solid #ccc;
  padding-right: 20px;
}

#filters button {
  width: auto;
  margin: 0 12px;
}

.filter {
  margin: 24px 0;
}

.filter p {
  margin: 8px 0;
}

#list {
  padding: 0 32px;
}

.form-check-label {
  display: inline;
}

.course {
  margin: 24px;
  padding: 32px;
  box-shadow: 1px 4px 8px #a5b6cf88;
  border-radius: 8px;
  background-color: white;
}

small {
  display: block;
  color: grey;
  margin: 4px 0 8px 0;
}

.course a {
  font-size: 24px;
}

@media screen and (max-width: 770px) {
  #filters {
    border-right: 0;
    padding-right: 0;
    border-bottom: 1px solid #ccc;
    padding-bottom: 20px;
  }
  .filter {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
  }
  .filter .form-check {
    display: inline-flex;
    align-items: center;
    margin: 0;
  }
  .form-check-label {
    margin: 0 8px;
  }
  #list {
    padding: 32px 0;
  }
}
</style>
