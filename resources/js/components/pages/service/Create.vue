<template>
  <form @submit.prevent="saveCategory">
    <app-card :title="edit ? 'Edit ' + name : 'Add New <b>Service</b>'">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image
            v-model="image"
            :image-url="imageUrl"
            width="150"
            height="150"
          ></input-image>
          <small
            class="text-center text-danger"
            v-if="serverErrors.get('image')"
            >* {{ serverErrors.get("image") }}</small
          >
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            v-model="name"
            placeholder="Enter Service Name"
            required
          ></input-text>

          <input-text
            label="Slug"
            name="slug"
            v-validate="'required'"
            :error-text="errors.first('slug') || serverErrors.get('slug')"
            v-model="slug"
            required
          ></input-text>

          <input-text
            label="Subtitle"
            name="subtitle"
            :error-text="errors.first('subtitle')"
            v-model="subtitle"
            placeholder="Enter Subtitle"
          ></input-text>

          <div class="row">
            <div class="col-md-3">
              <input-checkbox
                label="Enable Service"
                v-model="enabled"
              ></input-checkbox>
            </div>
            <div class="col-md-3">
              <input-checkbox
                label="Is OnDemand Service"
                v-model="ondemand"
              ></input-checkbox>
            </div>
            <div class="col-md-3">
              <input-checkbox
                label="Show Product Categories"
                v-model="showProductCategory"
              ></input-checkbox>
            </div>
            <div class="col-md-3">
              <input-checkbox
                label="Enable Opening Closing Time"
                v-model="openingClosingTime"
              ></input-checkbox>
            </div>
          </div>
        </div>
      </div>

      <div class="my-divider" v-if="hasSubCategories"></div>

      <div class="row" v-if="hasSubCategories">
        <div class="col-md-12">
          <h3 class="my-sub-heading">Sub Services</h3>

          <div
            class="row"
            v-for="(subCategory, index) in subCategories"
            :key="index"
          >
            <div class="col-md-3">
              <div
                class="form-group"
                :class="
                  subCategoriesAreFromServer(index) ? 'show-image-on-hover' : ''
                "
              >
                <label>
                  <span>Image</span>
                  <span
                    v-if="
                      subCategoriesAreFromServer(index) &&
                      typeof subCategories[index].image === 'string'
                    "
                    class="material-icons"
                    style="position: absolute; top: 0; left: 50px"
                    >remove_red_eye</span
                  >
                </label>
                <input
                  type="text"
                  readonly
                  placeholder="Choose Image"
                  class="form-control"
                />
                <input
                  type="file"
                  @change="initializeImage(index, $event)"
                  class="form-control"
                />
                <small
                  class="text-danger"
                  v-if="serverErrors.has('sub_images.' + index)"
                  >* {{ serverErrors.get("sub_images." + index) }}</small
                >
                <img
                  v-if="edit && typeof subCategories[index].image === 'string'"
                  :src="subCategories[index].image"
                  style="display: none"
                />
              </div>
            </div>

            <div class="col-md-4">
              <input-text
                label="Name"
                v-model="subCategories[index].name"
                @keyup="makeSlug(index)"
                @blur="makeSlug(index)"
                required
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="Slug"
                v-model="subCategories[index].slug"
                :name="'subSlug.' + index"
                v-validate="'required'"
                @input="serverErrors.clear('sub_slugs.' + index)"
                :error-text="
                  errors.first('subSlug.' + index) ||
                  serverErrors.get('sub_slugs.' + index)
                "
                required
              ></input-text>
            </div>

            <div class="col-md-1 text-center">
              <a
                href="#"
                title="Delete Sub-service"
                class="btn btn-danger btn-sm"
                @click.prevent="removeSubCategory(index)"
                style="margin-top: 40px"
              >
                <span class="material-icons">delete</span>
              </a>
            </div>
          </div>

          <br />
          <a
            href="#"
            v-if="!addLimitIsReached"
            class="btn btn-warning btn-sm"
            @click.prevent="addSubCategory"
          >
            <span class="material-icons">add</span> Add Sub Service
          </a>
        </div>
      </div>

      <div class="text-right">
        <button
          type="submit"
          class="btn btn-success btn-fill"
          :disabled="errors.any()"
          v-text="edit ? 'Update' : 'Save'"
        ></button>
      </div>
    </app-card>
  </form>
</template>

<script>
import { CATEGORY_INDEX_URL } from "@routes/admin";
import Error from "@utils/Error";
import { mapActions } from "vuex";

export default {
  name: "ServiceCreate",

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      name: "",
      image: "",
      enabled: true,
      ondemand: false,
      openingClosingTime: false,
      showProductCategory: true,
      subtitle: "",
      slug: "",
      hasSubCategories: false,
      subCategories: [],
      cache: new ModelCache("category"),
      serverErrors: new Error(),
    };
  },

  methods: {
    ...mapActions(["updateThisMonthCategoriesCount"]),

    addSubCategory() {
      this.subCategories.push({ id: 0, image: "", name: "", slug: "" });
    },

    removeSubCategory(index) {
      this.serverErrors.clear();

      if (this.subCategoriesAreFromServer(index))
        this.removeSubCategoryPermanently(index);
      else this.removeSubCategoryTemporarily(index);
    },

    subCategoriesAreFromServer: function (index) {
      return this.subCategories[index].id > 0;
    },

    removeSubCategoryPermanently: function (index) {
      if (confirm("Are you sure? It will be permanently deleted")) {
        axios
          .delete(CATEGORY_INDEX_URL + "/" + this.subCategories[index].id)
          .then((response) => {
            this.removeSubCategoryTemporarily(index);
            this.cache.invalidate();
            this.updateThisMonthCategoriesCount(-1);
          });
      }
    },

    removeSubCategoryTemporarily: function (index) {
      this.subCategories.splice(index, 1);
    },

    makeSlug: function (index) {
      if (this.subCategories[index].id === 0)
        this.subCategories[index].slug = this.subCategories[index].name.slug();
    },

    initializeImage(index, event) {
      this.subCategories[index].image = event.target.files[0];
    },

    saveCategory() {
      this.$validator.validate().then((result) => {
        if (result) {
          this.edit ? this.updateCategory() : this.storeCategory();
        } else {
          Helpers.focusFirstError(this.errors);
        }
      });
    },

    storeCategory() {
      axios
        .post(CATEGORY_INDEX_URL, this.formData)
        .then((response) => {
          alertMessage("Category saved successfully.");
          this.cache.invalidate();
          this.updateThisMonthCategoriesCount(1 + this.newSubCategories.length);
          this.$router.push({ name: "service.index" });
        })
        .catch(({ response }) => {
          response.status === 422
            ? this.serverErrors.initialize(response.data.errors)
            : alertMessage(
                "Something went wrong! Please Try again later.",
                "danger"
              );
        });
    },

    updateCategory() {
      axios
        .post(CATEGORY_INDEX_URL + "/" + this.$route.params.id, this.formData)
        .then((response) => {
          alertMessage("Category updated successfully.");
          // this.imageUrl = response.data.category.image;
          // this.subCategories = response.data.category.children;
          this.cache.invalidate();
          this.updateThisMonthCategoriesCount(this.newSubCategories.length);
          this.$router.push({ name: "service.index" });
        })
        .catch(({ response }) => {
          response.status === 422
            ? this.serverErrors.initialize(response.data.errors)
            : alertMessage(
                "Something went wrong! Please Try again later.",
                "danger"
              );
        });
    },

    getCategory(id) {
      axios
        .get(CATEGORY_INDEX_URL + "/" + (id || this.$route.params.id))
        .then((response) => {
          let category = response.data.category;
          this.name = category.name;
          this.slug = category.slug;
          this.enabled = category.enabled;
          this.ondemand = category.ondemand;
          this.openingClosingTime = category.opening_closing_time;
          this.showProductCategory = category.show_product_category;
          this.subtitle = category.subtitle;
          this.imageUrl = category.image;

          this.hasSubCategories = category.children.length > 0;

          this.subCategories = response.data.subCategories;
        });
    },
  },

  computed: {
    addLimitIsReached: function () {
      let newSubCategories = this.subCategories.filter(function (subCategory) {
        return subCategory.id === 0;
      });

      return newSubCategories.length >= 10;
    },

    newSubCategories() {
      return this.subCategories.filter((a) => a.id === 0);
    },

    formData() {
      let formData = new FormData();
      formData.append("image", this.image);
      formData.append("name", this.name);
      formData.append("slug", this.slug);
      formData.append("enabled", this.enabled);
      formData.append("ondemand", this.ondemand);
      formData.append("opening_closing_time", this.openingClosingTime);
      formData.append("show_product_category", this.showProductCategory);
      formData.append("subtitle", this.subtitle);
      formData.append("has_sub_categories", this.hasSubCategories ? 1 : 0);

      this.subCategories.forEach((item, key) => {
        formData.append(`sub_ids[${key}]`, item.id);
        formData.append(
          `sub_images[${key}]`,
          typeof item.image === "string" ? "" : item.image
        );
        formData.append(`sub_names[${key}]`, item.name);
        formData.append(`sub_slugs[${key}]`, item.slug);
      });

      if (this.edit) {
        formData.append("_method", "PUT");
      }

      return formData;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getCategory();
    }
  },

  watch: {
    name(val) {
      if (!this.edit) this.slug = val.slug();
    },

    slug(val) {
      this.serverErrors.clear("slug");
    },

    hasSubCategories(val) {
      if (val === false) {
        this.subCategories = this.subCategories.filter((val) => val.id !== 0);
      }
    },
  },
};
</script>
