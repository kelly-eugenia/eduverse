import { createStore } from "vuex";
import axios from "axios";

export const store = createStore({
  state() {
    return {
      user: null,
    };
  },
  getters: {
    user: (state) => state.user,
    role: (state) => state.user?.role,
    userID: (state) => state.user?.userID,
    fullName: (state) => state.user?.firstName + " " + state.user?.lastName,
    username: (state) => state.user?.username,
    email: (state) => state.user?.email,
    likedCourses: (state) => state.user?.likedCourses || [],
    enrolledCourses: (state) => state.user?.enrolledCourses || [],
    addedCourses: (state) => state.user?.addedCourses || [],
  },
  mutations: {
    setUser(state, payload) {
      state.user = payload;
    },
    logout(state) {
      state.user = null;
      localStorage.removeItem("user");
    },
    enrollCourse(state, course) {
      state.user.enrolledCourses.push(course);
    },
    likeCourse(state, course) {
      state.user.likedCourses.push(course);
    },
    unlikeCourse(state, course) {
      const index = state.user.likedCourses.indexOf(course);
      state.user.likedCourses.splice(index, 1);
    },
    addCourse(state, course) {
      state.user.addedCourses.push(course);
    },
    deleteCourse(state, course) {
      const index = state.user.addedCourses.indexOf(course);
      state.user.addedCourses.splice(index, 1);
    },
  },
  actions: {
    login({ commit }, { username, password }) {
      return axios
        .post(
          "https://mercury.swin.edu.au/cos30043/s104475686/Project/resources/process_login.php",
          { username, password }
        )
        .then((res) => {
          if (res.data.success) {
            console.log("LOGIN RESPONSE", res.status, res.data);

            commit("setUser", res.data);
            localStorage.setItem("user", JSON.stringify(res.data));

            return true;
          }
        })
        .catch((error) => {
          console.error("Failed to login:", error);

          commit("setUser", null);

          return false;
        });
    },
  },
});
