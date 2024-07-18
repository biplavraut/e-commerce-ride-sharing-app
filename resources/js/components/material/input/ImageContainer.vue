<template>
  <div class="imagecontainer" v-if="show">
    <img :src="image.image" />
    <a class="delete-image" @click="deleteMyImage()">Delete</a>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ImageContainer",

  props: {
    width: {
      default: 150,
    },
    height: {
      default: 150,
    },
    image: {},
  },

  data() {
    return {
      show: true,
    };
  },

  methods: {
    deleteMyImage() {
      axios
        .post("/vendor/product/delete-image", {
          id: this.image.id,
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
            Accept: "application/json",
          },
        })
        .then((response) => {});
      alertMessage("Image has been deleted successfully.");
      this.show = false;
    },
  },
};
</script>

<style scoped>
img {
  width: 100%;
  height: 100%;
}
.delete-image {
  opacity: 0.9;
  background: red;
  color: white;
  width: 100%;
  display: block;
  cursor: pointer;
}
.imagecontainer {
  margin: 0 5px;
  display: inline-block;
}
</style>