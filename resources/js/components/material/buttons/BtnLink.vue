<template>
  <router-link
    :to="{
      name: routeName,
      params: { data: data },
    }"
    class="btn btn-round btn-xs title-right-action"
    :class="'btn-' + background"
  >
    <i class="material-icons">{{ icon }}</i>
    <slot>Btn Text</slot>
  </router-link>
</template>

<script>
export default {
  name: "BtnLink",

  props: {
    routeName: {
      type: String,
      default: "",
    },
    background: {
      type: String,
      default: "success",
    },
    icon: {
      type: String,
      default: "add_circle_outline",
    },
    data: {
      default: "",
    },
  },
};
</script>

<style scoped>
.title-right-action {
  right: 15px;
  top: 6px;
  float: right;
  margin-left: 10px;
}
</style>