<template>
  <header>
    <h1><strong>Unlock Your Future with Eduverse!</strong></h1>
    <p>Gain the knowledge and skills you need to advance with our courses!</p>
    <router-link to="/login" class="btn btn-primary"
      >Start learning</router-link
    >
  </header>

  <div class="container">
    <section id="about">
      <div class="row align-items-center">
        <div class="col-md-12 col-lg-6 align-items-center">
          <div class="aboutContent">
            <img src="../assets/education.jpeg" alt="education" />
          </div>
        </div>
        <div class="col-md-12 col-lg-6 align-items-center">
          <div class="aboutContent">
            <h1>🚀 Empower Your Career<br />—One Skill at a Time</h1>
            <p>
              Eduverse is more than an online course platform — it’s your
              launchpad to career growth. Whether you’re starting out, switching
              fields, or upskilling for the future, our expertly designed
              courses and flexible learning paths put you in control.
            </p>
            <router-link to="/about" class="btn btn-primary"
              >Learn more</router-link
            >
          </div>
        </div>
      </div>

      <div
        class="row align-items-center flex-column-reverse flex-md-row"
        id="whyUs"
      >
        <div class="col-md-12 col-lg-6 align-items-center">
          <div class="aboutContent">
            <h1>💡 Why Eduverse?</h1>
            <ul>
              <li>Industry-aligned courses built by professionals</li>
              <li>Learn anytime, anywhere, on any device</li>
              <li>Track progress and earn certificates</li>
              <li>Continuously updated content to stay relevant</li>
            </ul>
          </div>
        </div>
        <div class="col-md-12 col-lg-6 align-items-center">
          <div class="aboutContent">
            <img src="../assets/online course.jpeg" alt="online course" />
          </div>
        </div>
      </div>
    </section>
  </div>

  <section class="container-fluid" id="courses">
    <h1><strong>Explore Our Courses</strong></h1>
    <p>
      Choose from our wide range of industry-focused courses designed to help
      you grow in the world’s most in-demand fields.
    </p>

    <div class="d-flex flex-wrap justify-content-center">
      <div class="col-12 col-md-4 col-lg-2">
        <div class="category">
          <h3>📊 Business</h3>
          <small>Entrepreneurship, Analytics, Strategy, Leadership</small>
        </div>
      </div>
      <div class="col-12 col-md-4 col-lg-2">
        <div class="category">
          <h3>💰 Finance</h3>
          <small>Personal Finance, Investing, Accounting, FinTech</small>
        </div>
      </div>
      <div class="col-12 col-md-4 col-lg-2">
        <div class="category">
          <h3>🎨 Design</h3>
          <small>UI/UX, Graphic Design, Prototyping Tools</small>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-2">
        <div class="category">
          <h3>💻 IT & Computer Science</h3>
          <small>Web Dev, Programming, Cybersecurity, Cloud</small>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-2">
        <div class="category">
          <h3>📈 Marketing</h3>
          <small>Digital Ads, SEO, Branding, Analytics</small>
        </div>
      </div>
    </div>

    <router-link to="/courses" class="btn btn-primary"
      >Browse all courses</router-link
    >
  </section>
  <section id="join">
    <h1>📈 Join Thousands Already Learning</h1>
    <p>
      Join 50,000+ learners on Eduverse and start building the skills that
      matter. Whether you're climbing the ladder or switching paths — we're here
      to help.
    </p>
    <router-link to="/register" class="btn btn-primary">Join us</router-link>
  </section>
</template>

<script></script>

<style scoped>
a {
  margin: 12px 0;
}

header {
  background-image: url("../assets/hero.jpeg");
  background-color: #d9f2fc;
  background-size: cover;
  text-align: center;
  padding: 88px 24px;
}

img {
  width: 100%;
}

section {
  margin: 24px 0;
  text-align: center;
}

#about {
  text-align: left;
  margin-bottom: 0;
}

.aboutContent {
  padding: 16px;
}

#whyUs {
  margin-top: 24px;
}

#courses {
  background-color: #d0e8f3;
  padding: 48px 24px;
  margin-top: -64px;
}

.category {
  background-color: white;
  padding: 40px 24px;
  box-shadow: 1px 5px 8px #a5b6cf88;
  border-radius: 8px;
  margin: 12px;
  text-align: center;
  width: auto;
}
</style>
