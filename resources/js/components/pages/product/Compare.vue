<template>
  <app-card :title="'Comparing <b>' + form.title + '</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-10">
              <h3 class="my-sub-heading color">Original</h3>
            </div>
          </div>

          <div class="row">
            <div
              :class="
                subCategories.length > 0 && subChildCategories.length > 0
                  ? 'col-md-4'
                  : subCategories.length > 0
                  ? 'col-md-6'
                  : 'col-md-12'
              "
            >
              <input-select
                v-model="form.productCategoryId"
                name="category"
                label="Category"
                @input="getSubCategory(form.productCategoryId)"
                :options="categories"
              ></input-select>
              <small
                class="text-center text-danger"
                v-if="errors.any('category')"
                >* {{ errors.first("category") }}</small
              >
            </div>

            <div
              :class="
                subCategories.length > 0 && subChildCategories.length > 0
                  ? 'col-md-4'
                  : subCategories.length > 0
                  ? 'col-md-6'
                  : 'hide'
              "
            >
              <input-select
                v-model="subCategoryId"
                label="Sub-Category"
                @input="getSubChildCategory(subCategoryId)"
                :options="subCategories"
              ></input-select>
            </div>

            <div :class="subChildCategories.length > 0 ? 'col-md-4' : 'hide'">
              <input-select
                v-model="subChildCategoryId"
                label="Sub-Child-Category"
                :options="subChildCategories"
              ></input-select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Name"
                name="title"
                v-model="form.title"
                v-validate="'required'"
                :error-text="errors.first('title')"
                required
              ></input-text>
            </div>
            <div class="col-md-6">
              <input-text
                label="Slug"
                name="slug"
                v-validate="'required'"
                :error-text="errors.first('slug')"
                v-model="form.slug"
                required
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <input-text
                label="Stock"
                type="number"
                name="opening_stock"
                v-model="form.openingStock"
                min="0"
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Size</label>
              <input-tags
                element-id="sizes"
                v-model="form.size"
                typeahead-style="dropdown"
                :typeahead="true"
                :existing-tags="sizes"
              ></input-tags>
            </div>
            <div class="col-md-6">
              <label>Color</label>
              <input-tags
                element-id="colors"
                v-model="form.color"
                typeahead-style="dropdown"
                :typeahead="true"
                :existing-tags="colors"
              ></input-tags>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <input-text
                label="Batch No"
                type="number"
                name="batch_no"
                v-model="form.batchNo"
              ></input-text>
            </div>
            <div class="col-md-4">
              <input-text
                label="Expiration Date"
                type="date"
                name="expire_date"
                v-model="form.expireDate"
              ></input-text>
            </div>
            <div class="col-md-4">
              <input-text
                label="Unit"
                type="text"
                name="unit"
                v-model="form.unit"
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="row">
              <div class="col-md-10">
                <h3 class="my-sub-heading">Price Related</h3>
              </div>
            </div>

            <div class="col-md-6">
              <input-select
                v-model="form.discountType"
                name="discount_type"
                label="Discount Type"
                :options="discountTypes"
              ></input-select>
            </div>
            <div class="col-md-6">
              <input-text
                v-model="form.discount"
                type="number"
                min="0"
                max="100"
                label="Discount"
                name="discount"
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="MRP Price"
                type="text"
                name="price"
                v-model="form.price"
                v-validate="'required'"
                :error-text="errors.first('price')"
                required
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="Vat Percentage"
                type="number"
                name="vatPercentage"
                v-model="form.vatPercentage"
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="Service Charge Percentage"
                type="number"
                name="serviceChargePercentage"
                v-model="form.serviceChargePercentage"
              ></input-text>
            </div>
          </div>

          <app-quill-editor
            label="Description"
            name="description"
            :key="0"
            v-model="form.description"
            :error-text="errors.first('description')"
          ></app-quill-editor>

          <div class="text-right">
            <button
              type="button"
              class="btn btn-danger"
              @click.prevent="revert()"
            >
              Revert to Original
            </button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row text-right">
            <div class="col-md-10">
              <h3 class="my-sub-heading color">Updates By Vendor</h3>
            </div>
          </div>

          <div class="row">
            <div
              :class="
                subCategories.length > 0 && subChildCategories.length > 0
                  ? 'col-md-4'
                  : subCategories.length > 0
                  ? 'col-md-6'
                  : 'col-md-12'
              "
            >
              <input-select
                v-model="form.update.productCategoryId"
                name="category"
                label="Category"
                @input="getSubCategory(form.update.productCategoryId)"
                :options="categories"
              ></input-select>
              <small
                class="text-center text-danger"
                v-if="errors.any('category')"
                >* {{ errors.first("category") }}</small
              >
            </div>

            <div
              :class="
                subCategories.length > 0 && subChildCategories.length > 0
                  ? 'col-md-4'
                  : subCategories.length > 0
                  ? 'col-md-6'
                  : 'hide'
              "
            >
              <input-select
                v-model="updateSubCategoryId"
                label="Sub-Category"
                @input="getSubChildCategory(updateSubCategoryId)"
                :options="subCategories"
              ></input-select>
            </div>

            <div :class="subChildCategories.length > 0 ? 'col-md-4' : 'hide'">
              <input-select
                v-model="updateSubChildCategoryId"
                label="Sub-Child-Category"
                :options="subChildCategories"
              ></input-select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Name"
                name="title"
                v-model="form.update.title"
                v-validate="'required'"
                :error-text="errors.first('title')"
                required
              ></input-text>
            </div>
            <div class="col-md-6">
              <input-text
                label="Slug"
                name="slug"
                v-validate="'required'"
                :error-text="errors.first('slug')"
                v-model="form.update.slug"
                required
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <input-text
                label="Stock"
                type="number"
                name="opening_stock"
                v-model="form.update.openingStock"
                min="0"
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Size</label>
              <input-tags
                element-id="sizes"
                v-model="form.update.size"
                typeahead-style="dropdown"
                :typeahead="true"
                :existing-tags="sizes"
              ></input-tags>
            </div>
            <div class="col-md-6">
              <label>Color</label>
              <input-tags
                element-id="colors"
                v-model="form.update.color"
                typeahead-style="dropdown"
                :typeahead="true"
                :existing-tags="colors"
              ></input-tags>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <input-text
                label="Batch No"
                type="number"
                name="batch_no"
                v-model="form.update.batchNo"
              ></input-text>
            </div>
            <div class="col-md-4">
              <input-text
                label="Expiration Date"
                type="date"
                name="expire_date"
                v-model="form.update.expireDate"
              ></input-text>
            </div>
            <div class="col-md-4">
              <input-text
                label="Unit"
                type="text"
                name="unit"
                v-model="form.update.unit"
              ></input-text>
            </div>
          </div>

          <div class="row">
            <div class="row">
              <div class="col-md-10">
                <h3 class="my-sub-heading">Price Related</h3>
              </div>
            </div>

            <div class="col-md-6">
              <input-select
                v-model="form.update.discountType"
                name="discount_type"
                label="Discount Type"
                :options="discountTypes"
              ></input-select>
            </div>
            <div class="col-md-6">
              <input-text
                v-model="form.update.discount"
                type="number"
                min="0"
                max="100"
                label="Discount"
                name="discount"
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="MRP Price"
                type="text"
                name="price"
                v-model="form.update.price"
                v-validate="'required'"
                :error-text="errors.first('price')"
                required
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="Vat Percentage"
                type="number"
                name="vatPercentage"
                v-model="form.update.vatPercentage"
              ></input-text>
            </div>

            <div class="col-md-4">
              <input-text
                label="Service Charge Percentage"
                type="number"
                name="serviceChargePercentage"
                v-model="form.update.serviceChargePercentage"
              ></input-text>
            </div>
          </div>

          <app-quill-editor
            label="Description"
            name="description"
            :key="0"
            v-model="form.update.description"
            :error-text="errors.first('description')"
          ></app-quill-editor>

          <div class="text-right">
            <button
              type="button"
              class="btn btn-success"
              @click.prevent="saveChange()"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import { mapGetters } from "vuex";

import Form from "@utils/Form";
import Vendor from "@utils/models/Vendor";
import Product from "@utils/models/Product";
import { store, save } from "@utils/mixins/Crud";
import "@voerro/vue-tagsinput/dist/style.css";
import axios from "axios";
import Error from "@utils/Error";
export default {
  name: "ProductCompare",

  mixins: [store, save],

  data() {
    return {
      form: new Form({
        productCategoryId: "",
        title: "",
        slug: "",
        code: "",
        price: "",
        hide: false,
        openingStock: 0,
        description: "",
        discountType: "amount",
        discount: 0,
        size: "",
        color: "",
        batchNo: "",
        expireDate: "",
        unit: "",
        vatPercentage: 0,
        serviceChargePercentage: 0,
        update: {},
      }),
      serverErrors: new Error(),
      tags: {},
      sizes: {
        S: "S",
        M: "M",
        L: "L",
        XL: "XL",
        XXL: "XXL",
      },
      colors: {
        blue: "blue",
        green: "green",
        red: "red",
        yellow: "yellow",
        purple: "purple",
        white: "white",
        black: "black",
      },
      edit: false,
      model: new Product(),
      categories: [],
      subCategories: [],
      subChildCategories: [],
      units: [],
      discountTypes: [
        {
          id: "amount",
          name: "Amount",
        },
        {
          id: "percent",
          name: "Percent",
        },
      ],
      images: [],
      subCategoryId: "",
      subChildCategoryId: "",
      updateSubCategoryId: "",
      updateSubChildCategoryId: "",
    };
  },

  methods: {
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        title,
        slug,
        description,
        price,
        category,
        subCategory,
        subChildCategory,
        openingStock,
        code,
        discountType,
        discount,
        size,
        color,
        batchNo,
        expireDate,
        unit,
        hide,
        serviceChargePercentage,
        vatPercentage,
        update,
      } = data.data;
      this.form = new Form({
        title,
        slug,
        price,
        description,
        productCategoryId: category.id,
        openingStock,
        code,
        discountType,
        discount,
        size,
        color,
        batchNo,
        expireDate,
        unit,
        hide,
        serviceChargePercentage,
        vatPercentage,
        update,
      });
      this.subCategoryId = subCategory.id;
      this.subChildCategoryId = subChildCategory.id;
      this.form.update.productCategoryId = update.category.id;
      this.updateSubCategoryId = update.subCategory.id;
      this.updateSubChildCategoryId = update.subChildCategory.id;
    },

    async getCategories() {
      let categories = await this.model.getCategory();
      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name + " (" + category.slug + ")",
        };
      });
    },

    async getSubCategory(value = "") {
      let subCategories = await this.model.getSubCategory(value);
      if (subCategories.data.length == 0) {
        this.subCategoryId = null;
        this.subChildCategoryId = null;
      }
      this.subCategories = subCategories.data.map((subCategory) => {
        return {
          id: subCategory.id,
          name: subCategory.name,
        };
      });
    },

    async getSubChildCategory(value = "") {
      let subChildCategories = await this.model.getSubCategory(value);
      if (subChildCategories.data.length == 0) {
        this.subChildCategoryId = null;
      }
      this.subChildCategories = subChildCategories.data.map(
        (subChildCategory) => {
          return {
            id: subChildCategory.id,
            name: subChildCategory.name,
          };
        }
      );
    },
    revert() {
      if (
        confirm("Are you sure? You want to process this action.") &&
        this.$route.params.id
      ) {
        axios
          .get(
            this.model.indexUrl +
              "/revert-vendor-update?id=" +
              this.$route.params.id
          )
          .then((response) => {
            if (response.data === "success") {
              alertMessage("Successfully Reverted to Original.");
              this.$router.push({
                name: `product.index`,
              });
            } else {
              alertMessage("Something went wrong.", "danger");
            }
          });
      }
    },
    saveChange() {
      if (
        confirm("Are you sure? You want to process this action.") &&
        this.$route.params.id
      ) {
        axios
          .get(
            this.model.indexUrl + "/update-change?id=" + this.$route.params.id
          )
          .then((response) => {
            if (response.data === "success") {
              alertMessage("Successfully updates the vendor changes.");
              this.$router.push({
                name: `product.index`,
              });
            } else {
              alertMessage("Something went wrong.", "danger");
            }
          });
      }
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getCategories();

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();

      this.getSubCategory(this.form.update.productCategoryId);
    }
  },

  created() {
    if (this.$route.params.idx) {
      this.$route.params.id = this.$route.params.idx;
    }
  },

  computed: {
    ...mapGetters(["authUser"]),
  },
  watch: {
    "form.productCategoryId": function (val) {
      if (this.edit) {
        this.getSubCategory(val);
      }
    },
    subCategoryId: function (val) {
      if (this.edit) {
        this.getSubChildCategory(val);
      }
    },
    updateSubCategoryId: function (val) {
      if (this.edit) {
        this.getSubChildCategory(val);
      }
    },
  },
};
</script>

<style scoped>
.color {
  background: #1b4bf9;
}
</style>
