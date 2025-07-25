<template>
  <div class="container">
    <h1>Explore The Latest News</h1>

    <!-- Filters -->
    <div class="row">
      <!-- Date -->
      <div class="form-group col-sm-6 col-md-3">
        <label for="fromDate"><strong>From</strong></label>
        <input
          type="date"
          v-model="fromDate"
          class="form-control"
          name="fromDate"
        />
      </div>
      <div class="form-group col-sm-6 col-md-3">
        <label for="toDate"><strong>To</strong></label>
        <input
          type="date"
          v-model="toDate"
          class="form-control"
          name="toDate"
        />
      </div>

      <!-- Category -->
      <div class="form-group col-sm-12 col-md-12 col-lg-6">
        <label><strong>Category</strong></label
        ><br />
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            v-model="category"
            value="all"
            id="all"
            checked
          />
          <label class="form-check-label" for="all">All</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            v-model="category"
            value="Business"
            id="business"
          />
          <label class="form-check-label" for="business">Business</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            v-model="category"
            value="Technology"
            id="technology"
          />
          <label class="form-check-label" for="technology">Technology</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            v-model="category"
            value="Design"
            id="design"
          />
          <label class="form-check-label" for="design">Design</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            v-model="category"
            value="Finance"
            id="finance"
          />
          <label class="form-check-label" for="finance">Finance</label>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Title -->
      <div class="form-group col-sm-12 col-md-6">
        <label for="title"><strong>Title</strong></label>
        <input
          type="text"
          v-model="title"
          class="form-control"
          id="title"
          placeholder="Search for title..."
        />
      </div>

      <!-- Content -->
      <div class="form-group col-sm-12 col-md-6">
        <label for="content"><strong>Content</strong></label>
        <input
          type="text"
          v-model="content"
          class="form-control"
          id="content"
          placeholder="Search for news content..."
        />
      </div>
    </div>

    <br />
    <hr />

    <!-- News -->
    <div class="row" id="newsList">
      <div
        v-for="(news, index) in paginatedNews"
        :key="index"
        class="col-sm-12 col-md-6 col-lg-4"
      >
        <Article :news="news"></Article>
      </div>

      <div v-show="paginatedNews.length === 0">No articles found.</div>

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
        id="paginate"
      >
      </Paginate>
    </div>
  </div>
</template>

<script>
import Paginate from "vuejs-paginate-next";
import Article from "../components/Article.vue";
import newsData from "../assets/news.json";

export default {
  data() {
    return {
      perPage: 6,
      currentPage: 1,
      fromDate: "",
      toDate: "",
      title: "",
      content: "",
      category: "all",
      newsList: newsData,
    };
  },
  components: {
    Paginate,
    Article,
  },
  computed: {
    filteredNews() {
      this.currentPage = 1;

      return this.newsList.filter((news) => {
        const newsDate = new Date(news.date);
        const fromDate = this.fromDate ? new Date(this.fromDate) : null;
        const toDate = this.toDate ? new Date(this.toDate) : null;

        const matchDate =
          (!fromDate || newsDate >= fromDate) &&
          (!toDate || newsDate <= toDate);

        const matchTitle =
          this.title == "" ||
          news.title.toLowerCase().includes(this.title.toLowerCase());

        const matchContent =
          this.content == "" ||
          news.content.toLowerCase().includes(this.content.toLowerCase());

        const matchCategory =
          this.category == "all" || news.category == this.category;

        return matchDate && matchTitle && matchContent && matchCategory;
      });
    },

    paginatedNews() {
      let current = this.currentPage * this.perPage;
      let start = current - this.perPage;
      return this.filteredNews.slice(start, current);
    },

    getPageCount: function () {
      return Math.ceil(this.filteredNews.length / this.perPage);
    },
  },

  methods: {
    //sets the clicked page
    clickCallback: function (pageNum) {
      this.currentPage = Number(pageNum);
    },
    truncate(text, maxLength) {
      if (!text) return "";
      return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
    },
  },
};
</script>

<style scoped>
h1 {
  text-align: center;
}

label,
p {
  text-align: left;
  margin: 24px 0 8px 0;
}

input::placeholder {
  font-size: small;
}

.form-check-label {
  display: inline;
}

#paginate {
  margin-top: 24px;
}
</style>
