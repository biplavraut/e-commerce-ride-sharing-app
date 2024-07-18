<template>
  <button class="btn btn-round btn-xs title-right-action"
          v-on="listeners"
          :class="'btn-'+background">
    <i class="material-icons"
       v-if="iconIsPresent">{{ icon }}</i>
    <slot>Btn Text</slot>
  </button>
</template>

<script>
  export default {
    name: "Btn",

    props: {
      background: {
        type: String,
        default: "success"
      },
      icon: {
        type: String,
        default: ""
      }
    },

    computed: {
      listeners() {
        return this.$listeners;
      },
      iconIsPresent() {
        return this.icon !== '';
      }
    }
  };
</script>

<style scoped>
  .title-right-action {
    right       : 15px;
    top         : 6px;
    float       : right;
    margin-left : 10px;
  }
</style>