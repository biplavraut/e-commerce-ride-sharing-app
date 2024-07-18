<template>
  <div class="text-center">
    <image-container
      v-for="image in pimages"
      :key="image.id"
      :image="image"
      :style="'width:' + width + 'px;height:' + height + 'px;'"
    ></image-container>
    <div
      class="image-container"
      :style="'width:' + width + 'px;height:' + height + 'px;'"
      v-for="(image, key) in images"
      :key="key"
    >
      <img :src="image.url" />
      <a class="delete-image" @click.prevent="deleteImage(key)">Delete</a>
    </div>

    <div
      class="image-container"
      :style="'width:' + width + 'px;height:' + height + 'px;'"
    >
      <input
        type="file"
        accept="image/*"
        @change="onFilePicked"
        ref="inputImageFile"
        multiple
        style="display: none"
      />
      <img :src="defaultUrl" @click="showFileSelectionDialogue" />
    </div>

    <div>
      <span class="text-label cursor-pointer" @click="showFileSelectionDialogue"
        >Choose {{ label }}
        <small v-if="required">*</small>
      </span>
    </div>
  </div>
</template>

<script>
import ImageContainer from "@components/material/utils/ImageContainer";
export default {
  name: "ImageContainerMultiple",
  components: {
    ImageContainer,
  },

  props: {
    value: {},
    imageUrl: { default: "/images/camera.png" },
    label: { default: "Images" },
    required: { type: Boolean, default: false },
    width: { default: 150 },
    height: { default: 150 },
    pimages: { default: () => [] },
  },

  data() {
    return {
      images: [],
      defaultUrl: "",
    };
  },

  methods: {
    onFilePicked(event) {
      let imageFiles = event.target.files;
      // previously added image files
      let previousImageFiles = this.onlyImageFiles;

      for (let i = 0; i < imageFiles.length; i++) {
        if (imageFiles[i].type.indexOf("image") > -1) {
          this.visualizeImage(imageFiles[i]);
        }
        previousImageFiles.push(imageFiles[i]);
      }

      this.$emit("input", previousImageFiles);
    },

    showFileSelectionDialogue() {
      this.$refs.inputImageFile.click();
    },

    visualizeImage(imageFile) {
      let fileReader = new FileReader();
      fileReader.addEventListener("load", () =>
        this.images.push({ url: fileReader.result, file: imageFile })
      );
      fileReader.readAsDataURL(imageFile);
    },

    deleteImage(index) {
      this.images.splice(index, 1);

      this.$emit("input", this.onlyImageFiles);
    },
  },

  computed: {
    onlyImageFiles() {
      return this.images.map((image) => image.file);
    },
  },

  mounted() {
    this.defaultUrl = "/images/camera.png";
  },
};
</script>

<style scoped lang="scss">
.image-container {
  background: #eeeeee;
  position: relative;
  overflow: hidden;
  border: 1px solid #eeeeee;
  cursor: pointer;
  margin: 0 5px;
  display: inline-block;
  img {
    width: 100% !important;
    height: 100% !important;
  }
  .delete-image {
    position: absolute;
    left: 0;
    bottom: 0;
    opacity: 0.9;
    background: red;
    color: white;
    width: 100%;
  }
}
</style>