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

      <app-footer></app-footer>
    </div>
  </div>
</template>

<script>
  import AppLeftSidebar from "./LeftSideBar";
  import AppNavbar from "./Navbar";
  import AppFooter from "./Footer";
  import {mapGetters, mapMutations} from "vuex";

  export default {
    name: "App",

    components: {
      AppLeftSidebar,
      AppNavbar,
      AppFooter
    },

    props: ["pSettings", "pAuthUser", "pCounts"],

    data() {
      return {};
    },

    methods: {
      ...mapMutations(["setSettings", "setAuthUser", "setHomePageCounts"])
    },

    computed: {
      ...mapGetters(["settings", "authUser", "csrf"])
    },

    created() {
      this.setSettings(JSON.parse(this.pSettings));
      this.setAuthUser(JSON.parse(this.pAuthUser));
      this.setHomePageCounts(JSON.parse(this.pCounts));
    }
  };
</script>

<style scoped>
  .page-transition-enter-active {
    animation : entering 0.8s;
    opacity   : 0;
  }

  @keyframes entering {
    from {
      -webkit-transform : scale(0, 0);
      -moz-transform    : scale(0, 0);
      -ms-transform     : scale(0, 0);
      -o-transform      : scale(0, 0);
      transform         : scale(0, 0);
    }
    to {
      -webkit-transform : scale(1, 1);
      -moz-transform    : scale(1, 1);
      -ms-transform     : scale(1, 1);
      -o-transform      : scale(1, 1);
      transform         : scale(1, 1);
      opacity           : 1;
    }
  }
</style>
