<template>
  <div class="card-root">
    <div class="loader-container"
         v-if="$store.getters.cardLoading">
      <app-loader class="center-loader"
                  :width="70"></app-loader>
    </div>
    <slot name="actions"></slot>

    <h3 class="card-title"
        v-html="title"></h3>

    <div class="card-content"
         :style="{padding: bodyPadding}">
      <slot></slot>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Card",

    props: {
      title      : {
        type    : String,
        required: true
      },
      bodyPadding: {
        type   : String,
        default: '15px'
      }
    },
  };
</script>

<style scoped>
  .card-root {
    position      : relative;
    margin-bottom : 20px;
  }

  .card-title {
    padding       : 10px 15px;
    margin        : 0;
    font-weight   : 400;
    background    : rgba(255, 255, 255, 0.6);
    color         : #666666;
    border-radius : 15px 15px 0 0;
    border-bottom : 1px dashed #CCCCCC;
  }

  .card-content {
    background-color : #FFFFFF;
  }
</style>