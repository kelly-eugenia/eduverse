import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/Home.vue";
import CourseList from "../components/CourseList.vue";
import Course from "../components/Course.vue";
import News from "../components/News.vue";
import About from "../components/About.vue";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import LearnerAccount from "../components/LearnerAccount.vue";
import InstructorAccount from "../components/InstructorAccount.vue";
import AddCourse from "../components/AddCourse.vue";
import EditCourse from "../components/EditCourse.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/courses", component: CourseList },
  { path: "/courses/:courseID", component: Course },
  { path: "/news", component: News },
  { path: "/about", component: About },
  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: "/account/learner", component: LearnerAccount },
  { path: "/account/instructor", component: InstructorAccount },
  { path: "/addcourse", component: AddCourse },
  { path: "/editcourse/:courseID", component: EditCourse },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});
export default router;
