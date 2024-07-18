<template>
  <div class="wrapper">
    <app-left-sidebar></app-left-sidebar>

    <div class="main-panel">
      <app-navbar></app-navbar>

      <div class="content">
        <div class="container-fluid">
          <transition name="page-transition">
            <router-view :key="$route.fullPath"></router-view>
          </transition>
        </div>
      </div>

      <a
        :href="logoutUrl"
        id="logout"
        style="display: none"
        onclick="event.preventDefault(); document.getElementById('logout-form1').submit();"
      >
        <span class="sidebar-mini">&nbsp;</span>
        <span class="sidebar-normal">Logout</span>
      </a>
      <form
        id="logout-form1"
        :action="logoutUrl"
        method="POST"
        style="display: none"
      >
        <input type="hidden" name="_token" :value="csrf" />
      </form>

      <app-footer></app-footer>
    </div>
  </div>
</template>

<script>
import AppLeftSidebar from "./LeftSideBar";
import AppNavbar from "./Navbar";
import AppFooter from "./Footer";
import { mapGetters, mapMutations } from "vuex";

import { LOGOUT_URL } from "@routes/admin";

export default {
  name: "App",

  components: {
    AppLeftSidebar,
    AppNavbar,
    AppFooter,
  },

  props: ["pSettings", "pAuthUser", "pCounts"],

  data() {
    return {};
  },

  methods: {
    ...mapMutations(["setSettings", "setAuthUser", "setHomePageCounts"]),

    showNotification: function (from, align, type, message = null) {
      message = message
        ? message
        : "Welcome to <b>gogo20</b> - Everyday solution.";

      $.notify(
        {
          // icon: "notifications",
          message: message,
        },
        {
          type: type, //type[color]
          timer: 3000,
          placement: {
            from: from,
            align: align,
          },
        }
      );
    },
  },

  computed: {
    ...mapGetters(["settings", "authUser", "csrf"]),

    logoutUrl() {
      return LOGOUT_URL;
    },
  },

  created() {
    this.setSettings(JSON.parse(this.pSettings));
    this.setAuthUser(JSON.parse(this.pAuthUser));
    this.setHomePageCounts(JSON.parse(this.pCounts));
  },
  mounted() {
    if (!this.authUser.verified) {
      document.getElementById("logout").click();
    }

    var orders = firebase.database().ref("orders/");

    orders.on("value", (snapshot) => {
      const data = snapshot.val();
      if (data) {
        this.showNotification(
          "top",
          "center",
          "success",
          "New Order Received.!!!"
        );
      }
    });
  },
};
</script>

<style scoped>
.page-transition-enter-active {
  animation: entering 0.8s;
  opacity: 0;
}

@keyframes entering {
  from {
    -webkit-transform: scale(0, 0);
    -moz-transform: scale(0, 0);
    -ms-transform: scale(0, 0);
    -o-transform: scale(0, 0);
    transform: scale(0, 0);
  }
  to {
    -webkit-transform: scale(1, 1);
    -moz-transform: scale(1, 1);
    -ms-transform: scale(1, 1);
    -o-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
}
</style>
