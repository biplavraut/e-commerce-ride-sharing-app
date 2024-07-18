<template>
  <div class="list">
    <slot />
  </div>
</template>

<script>
  import { ContainerMixin } from 'vue-slicksort';

  export default {
    name: "SortableList",

    mixins: [ContainerMixin],
  }
</script>

<style scoped>
  .list {
    position         : relative;
    min-height       : 200px;
    margin           : 0 auto;
    padding          : 0;
    overflow         : auto;
    /*background-color : #FFFFFF91;*/
    border           : 1px solid #EFEFEF;
    list-style-type  : none;
  }
</style>