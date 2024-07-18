<template>
  <app-card
    :title="edit ? 'Edit Website Slider' : 'Add New <b>Website Slider</b>'"
  >
    <template slot="actions">
      <app-btn-link route-name="website-slider.create">Add New</app-btn-link>
    </template>
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            label=" Image (1920px * 820px) *"
            title="1920px * 820px"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text
            label="Slider Caption Text"
            name="name"
            v-model="form.sliderText"
            :error-text="errors.first('name')"
          ></input-text>

          <input-checkbox label="Hide" v-model="form.hide"></input-checkbox>

          <button
            type="submit"
            :disabled="this.form.errors.any(errors.any())"
            class="btn btn-success pull-right"
          >
            {{ edit ? "Update" : "Save" }}
          </button>
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import WebsiteSlider from "@utils/models/WebsiteSlider";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "WebsiteSliderCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        sliderText: "",
        image: "",
        hide: false,
      }),
      model: new WebsiteSlider(),
    };
  },

  methods: {
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image;
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({ name: `website-slider.index` });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { sliderText, image, url, hide } = data.data;

      this.form = new Form({
        sliderText,
        image: "",
        hide,
      });

      this.imageUrl = image;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();
    }
  },

  watch: {
    "form.image": function (val) {
      let type = typeof val;
      if (type === "object") {
        this.form.errors.clear("image");
      }
    },
  },
};
</script>
