<template>
  <tr class="list-item">
    <slot></slot>
  </tr>
</template>

<script>
  import { ElementMixin } from 'vue-slicksort';

  export default {
    name: "SortableItem",
    props: ['item'],

    mixins: [ElementMixin],
  }
</script>

<style scoped>
  .list-item {
    width             : 100%;
    padding           : 5px;
    background-color  : #FFFFFF;
    border-bottom     : 1px solid #EFEFEF;
    box-sizing        : border-box;
    user-select       : none;
    color             : #333333;
    font-weight       : 400;
  }
</style>