<template>
  <div :tabindex="$vnode.key||0">
    <div class="image-container"
         :style="'width:'+width+'px;height:'+height+'px;'"
         :title="'Click to choose' + label">
      <input type="file"
             accept="image/*"
             :name="name"
             @change="onFilePicked"
             ref="inputImageFile">
      <img :src="imageUrlInside"
           @click="showFileSelectionDialogue">
      <a href="#"
         class="delete-image"
         v-if="showDelete"
         @click.prevent="deleteImage">Clear</a>
    </div>
    <div class="text-center">
			<span class="text-label cursor-pointer"
            @click="showFileSelectionDialogue">Choose {{ label }}
				<small v-if="required">*</small>
			</span>
    </div>
    <small class="text-danger text-center"
           style="display: block;"
           v-if="hasError">* {{ errorText }}
    </small>
  </div>
</template>

<script>
  export default {
    name: "ImageContainer",

    props: {
      value    : {},
      name     : {type: String, default: "image"},
      imageUrl : {default: "/images/camera.png"},
      label    : {default: "Image"},
      required : {type: Boolean, default: false},
      width    : {default: 200},
      height   : {default: 200},
      errorText: {type: String, default: ""},
    },

    data() {
      return {
        showDelete    : false,
        imageFile     : "",
        imageUrlInside: ""
      };
    },

    methods: {
      onFilePicked(event) {
        this.imageFile = event.target.files[0] || "";

        if (this.imageFile !== "" && this.imageFile.type.indexOf("image") > -1) {
          this.visualizeImage();
        } else {
          this.deleteImage();
        }
      },

      showFileSelectionDialogue() {
        this.$refs.inputImageFile.click();
      },

      deleteImage() {
        this.imageFile = this.$refs.inputImageFile.value = "";
        this.imageUrlInside = this.imageUrl;
        this.$emit("input", this.imageFile);
      },

      visualizeImage() {
        this.$emit("input", this.imageFile);
        let fileReader = new FileReader();
        fileReader.addEventListener(
          "load",
          () => (this.imageUrlInside = fileReader.result)
        );
        fileReader.readAsDataURL(this.imageFile);
      }
    },

    computed: {
      hasError() {
        return this.errorText !== "";
      },
    },

    mounted() {
      this.imageUrlInside = this.imageUrl;
    },

    watch: {
      imageFile(val) {
        this.showDelete = val !== "";
      },
      imageUrl(val) {
        this.imageUrlInside = val;
        this.showDelete = false;
      }
    }
  };
</script>

<style scoped
       lang="scss">
  .image-container {
    background: #EEEEEE;
    position: relative;
    overflow: hidden;
    border: 1px solid #EEEEEE;
    cursor: pointer;
    margin: 0 auto;

    input[type="file"] {
      position: absolute;
      z-index: -1;
      width: 100%;
    }

    img {
      width: 100% !important;
      height: 100% !important;
    }

    .delete-image {
      position: absolute;
      left: 0;
      bottom: 0;
      right: 0;
      opacity: 0.9;
      text-align: center;
      background: red;
      color: white;
      height: 30px;
      line-height: 30px;
      font-weight: 500;
    }
  }
</style>