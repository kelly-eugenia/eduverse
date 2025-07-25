<template>
  <div id="article">
    <h5>
      <a :href="news.link" target="_blank">{{ news.title }}</a>
    </h5>
    <small>{{ news.author }}</small>
    <p>{{ truncate(news.content, 100) }}</p>
    <small style="color: grey">{{ new Date(news.date).toDateString() }}</small>
  </div>
</template>

<script>
import newsData from "../assets/news.json";

export default {
  data() {
    return {
      newsList: newsData,
    };
  },
  props: {
    news: {},
  },
  computed: {
    news() {
      return this.newsList.find((n) => n === this.news);
    },
  },
  methods: {
    truncate(text, maxLength) {
      if (!text) return "";
      return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
    },
  },
};
</script>

<style scoped>
#article {
  margin: 24px auto;
  padding: 32px;
  box-shadow: 1px 4px 8px #a5b6cf88;
  border-radius: 8px;
  background-color: white;
}
</style>
