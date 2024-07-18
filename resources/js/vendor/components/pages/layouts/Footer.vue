<template>
  <footer class="footer">
    <div class="container-fluid">
      <p class="copyright pull-right">
        &copy;
        <router-link :to="{name: 'home'}">{{ settings.name }}</router-link>,
        <span>{{ year }}</span>
      </p>
    </div>
  </footer>
</template>

<script>
  import Common from "./Common"

  export default {
    name: "Footer",

    extends: Common,

    data() {
      return {
        year: new Date().getFullYear()
      }
    }
  }
</script>
