<template>
  <app-card :title="edit ? 'Edit ' + form.name : 'Add New <b>Slider</b>'">
    <template slot="actions">
      <app-btn-link route-name="slider.create">Add New</app-btn-link>
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
            label=" Image (500px * 375px) *"
            title="500px * 375px"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            v-model="form.name"
            required
            :error-text="errors.first('name')"
          ></input-text>

          <input-select
            v-model="form.categoryId"
            name="category"
            label="Service"
            :options="categories"
          ></input-select>
          <small class="text-center text-danger" v-if="errors.any('category')"
            >* {{ errors.first("category") }}</small
          >

          <input-text
            label="URL"
            name="url"
            v-model="form.url"
            :error-text="errors.first('url')"
          ></input-text>

          <input-checkbox
            label="For LayoutManager"
            v-model="form.forLayout"
          ></input-checkbox>

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
import Category from "@utils/models/Category";
import Slider from "@utils/models/Slider";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "SliderCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        name: "",
        image: "",
        url: "",
        forLayout: false,
        categoryId: "",
      }),
      model: new Slider(),
      category: new Category(),
      categories: [],
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
        this.$router.push({ name: `slider.index` });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, image, url, category, forLayout } = data.data;

      this.form = new Form({
        name,
        image: "",
        url,
        forLayout,
        categoryId: category.id,
      });

      this.imageUrl = image;
    },
    async getCategories() {
      let categories = await this.category.getAll();

      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name,
        };
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getCategories();

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
