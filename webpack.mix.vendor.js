let mix = require("laravel-mix");

const COMMON_BASE_PATH = "/resources/js/vendor";

mix.webpackConfig({
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      vue$: "vue/dist/vue.esm.js",
      "@": __dirname + COMMON_BASE_PATH,
      "@components": __dirname + COMMON_BASE_PATH + "/components",
      "@pages": __dirname + COMMON_BASE_PATH + "/components/pages",
      "@layouts": __dirname + COMMON_BASE_PATH + "/components/pages/layouts",
      "@routes": __dirname + COMMON_BASE_PATH + "/routes",
      "@stores": __dirname + COMMON_BASE_PATH + "/stores",
      "@utils": __dirname + COMMON_BASE_PATH + "/utils"
    }
  }
});

mix
  .js("resources/js/vendor/app.js", "public/vendor/js")
  .extract([
    "axios",
    "laravel-echo",
    "lodash",
    "moment",
    "pluralise",
    "pusher-js",
    "quill-image-drop-module",
    "vee-validate",
    "vue",
    "vue-router",
    "vue-slicksort",
    "vue2-editor",
    "vuex"
  ]);
// .sass("resources/sass/asdh_admin.scss", "public/css")
// .sass("resources/sass/asdh.scss", "public/css")
// .sass("resources/sass/app.scss", "public/css")
// .sourceMaps();
