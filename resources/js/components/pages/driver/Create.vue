<template>
  <app-card :title="edit?'Edit '+form.title:'Add New Product ' +'<b>'+form.title+'</b>'">
    <form @submit.prevent="saveData">
      <div class="col-md-12">
        <ul class="nav nav-pills nav-pills-warning">
          <li class="active">
            <a href="#detail" data-toggle="tab" aria-expanded="true">Detail</a>
          </li>
          <li class>
            <a href="#options" data-toggle="tab" aria-expanded="false">Options</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="detail">
            <input-images
              v-model="form.images"
              :image-url="imageUrl"
              required
              :pimages="images"
              :label="'Image (%px * %px)'"
              title="%px * %px"
            ></input-images>

            <small
              class="text-center text-danger"
              v-if="form.errors.has('images')"
            >* {{ form.errors.get('images') }}</small>

            <div class="row">
              <div
                :class="(subCategories.length > 0 && subChildCategories.length > 0) ? 'col-md-4' : subCategories.length > 0 ? 'col-md-6' : 'col-md-12' "
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
                >* {{ errors.first('category') }}</small>
              </div>

              <div
                :class="(subCategories.length > 0 && subChildCategories.length > 0) ? 'col-md-4' : subCategories.length > 0 ? 'col-md-6' : 'hide'"
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
              <div class="col-md-6">
                <div class="form-group">
                  <input-text
                    label="Badge"
                    name="badge"
                    :error-text="errors.first('badge')"
                    v-model="form.badge"
                  ></input-text>
                </div>
              </div>
              <div class="col-md-6">
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
              <div class="col-md-4">
                <!-- <input-select
                  v-model="form.sellerId"
                  name="seller"
                  label="Seller"
                  :options="sellers"
                ></input-select>-->
              </div>
              <div class="col-md-4">
                <!-- <input-select
                  v-model="form.supplierId"
                  name="supplier"
                  label="Supplier"
                  :options="suppliers"
                ></input-select>-->
              </div>
            </div>

            <div class="row">
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
            </div>

            <div class="row">
              <div class="col-md-6">
                <input-text
                  label="Price"
                  type="text"
                  name="price"
                  v-model="form.price"
                  v-validate="'required'"
                  :error-text="errors.first('price')"
                  required
                ></input-text>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tags</label>
                  <input-tags
                    element-id="tags"
                    v-model="form.tags"
                    @typing="getTags"
                    typeahead-style="dropdown"
                    :existing-tags="tags"
                    :typeahead="true"
                  ></input-tags>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <input-text label="Batch No" type="number" name="batch_no" v-model="form.batchNo"></input-text>
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
                <input-select v-model="form.unit" name="unit" label="Unit" :options="units"></input-select>
              </div>
            </div>
            <app-quill-editor
              label="Description"
              name="description"
              :key="0"
              v-model="form.description"
              :error-text="errors.first('description')"
            ></app-quill-editor>
            <br />
          </div>

          <div class="tab-pane" id="options">
            <div class="row">
              <div class="col-md-2">
                <input-checkbox label="Top Generic" v-model="form.topGeneric"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Top Brand" v-model="form.topBrand"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Best Seller" v-model="form.bestSeller"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Featured" v-model="form.featured"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Prescription Required" v-model="form.prescriptionRequired"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Trending" v-model="form.trending"></input-checkbox>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <input-checkbox label="Hot" v-model="form.hot"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Seasonal" v-model="form.seasonal"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Dear &amp; Near" v-model="form.dearNear"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Offers" v-model="form.offers"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Deal of the day" v-model="form.deal"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Big Save" v-model="form.bigSave"></input-checkbox>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <input-checkbox label="Top Picks" v-model="form.topPick"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Send to others(Gift)" v-model="form.sendToOthers"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Explore Something New" v-model="form.somethingNew"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Share &amp; Earn" v-model="form.share"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="New Items" v-model="form.neew"></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox label="Stock Clearance" v-model="form.stockClearance"></input-checkbox>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <input-checkbox label="Hide" v-model="form.hide"></input-checkbox>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-right">
        <button
          type="submit"
          :disabled="errors.any()"
          class="btn btn-success"
        >{{ edit ? "Update" : "Save" }}</button>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Product from "@utils/models/Product";
// import SubCategory from "@utils/models/Product";
// import SubChildCategory from "@utils/models/Product";
// import Category from "@utils/models/Category";
import { store, save } from "@utils/mixins/Crud";
import VoerroTagsInput from "@voerro/vue-tagsinput";
import "@voerro/vue-tagsinput/dist/style.css";
import axios from "axios";
import Error from "@utils/Error";
export default {
  name: "VendorProductCreate",

  mixins: [store, save],

  data() {
    return {
      form: new Form({
        productCategoryId: "",
        title: "",
        slug: "",
        code: "",
        badge: "",
        price: "",
        price1: "",
        openingStock: 0,
        description: "",
        discountType: "",
        discount: 0,
        prescriptionRequired: false,
        batchNo: "",
        expireDate: "",
        unit: "",
        topGeneric: false,
        topBrand: false,
        bestSeller: false,
        trending: false,
        hot: false,
        seasonal: false,
        dearNear: false,
        offers: false,
        deal: false,
        bigSave: false,
        topPick: false,
        sendToOthers: false,
        somethingNew: false,
        share: false,
        neew: false,
        stockClearance: false,
        featured: false,
        hide: false,
        images: "",
        tags: [],
      }),
      serverErrors: new Error(),
      tags: {},
      edit: false,
      imageUrl: Helpers.cameraImage(),
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
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();

      if (this.subCategoryId) {
        this.form.productCategoryId = this.subCategoryId;
      }
      if (this.subChildCategoryId) {
        this.form.productCategoryId = this.subChildCategoryId;
      }

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `${this.model.nameLowerCase}.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
        if (this.form.errors.has("image")) Helpers.focusId("image");
        if (this.form.errors.has("icon")) Helpers.focusId("icon");
      }
    },

    async updateData() {
      if (this.subCategoryId) {
        this.form.productCategoryId = this.subCategoryId;
      }
      if (this.subChildCategoryId) {
        this.form.productCategoryId = this.subChildCategoryId;
      }

      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image150;
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `${this.model.nameLowerCase}.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        title,
        slug,
        description,
        badge,
        price,
        price1,
        images,
        category,
        subCategory,
        subChildCategory,
        openingStock,
        code,
        discountType,
        discount,
        prescriptionRequired,
        batchNo,
        expireDate,
        unit,
        topGeneric,
        topBrand,
        bestSeller,
        trending,
        hot,
        seasonal,
        dearNear,
        offers,
        deal,
        bigSave,
        topPick,
        sendToOthers,
        somethingNew,
        share,
        neew,
        stockClearance,
        featured,
        hide,
        tags,
      } = data.data;
      this.form = new Form({
        title,
        slug,
        price,
        price1,
        badge,
        description,
        images: "",
        productCategoryId: category.id,
        openingStock,
        code,
        discountType,
        discount,
        prescriptionRequired,
        batchNo,
        expireDate,
        unit,
        topGeneric,
        topBrand,
        bestSeller,
        trending,
        hot,
        seasonal,
        dearNear,
        offers,
        deal,
        bigSave,
        topPick,
        sendToOthers,
        somethingNew,
        share,
        neew,
        stockClearance,
        featured,
        hide,
        tags,
      });
      this.images = images;
      this.subCategoryId = subCategory.id;
      this.subChildCategoryId = subChildCategory.id;
    },
    async getCategories() {
      let categories = await this.model.getCategory();

      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name,
        };
      });
    },
    async getTags(value = "") {
      let mtags = await this.model.getTag(value);

      mtags.data.map((mtag) => {
        this.tags[mtag.name] = mtag.name;
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
    async getUnits(val = "") {
      let units = await this.model.getUnits(val);

      this.units = units.map((unit) => {
        return {
          id: unit.name,
          name: unit.name,
        };
      });
    },

    deleteMe(uri, id) {
      axios
        .post(uri, {
          id: id,
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
            Accept: "application/json",
          },
        })
        .then((response) => {});
      alertMessage("Action has been performed successfully.");
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getCategories();
    this.getTags();

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();
    }
  },

  computed: {
    randomCode() {
      return this.form.slug + "-" + Math.floor(Math.random() * 999 + 111);
    },
  },
  watch: {
    "form.title": function (val) {
      if (!this.edit) {
        this.form.slug = val.slug();
        this.form.code = this.randomCode;
      }
    },
    "form.productCategoryId": function (val) {
      if (this.edit) {
        this.getSubCategory(val);
      }
      this.getUnits(val);
    },
    subCategoryId: function (val) {
      if (this.edit) {
        this.getSubChildCategory(val);
      }
      this.getUnits(val);
    },
    subChildCategoryId: function (val) {
      this.getUnits(val);
    },
  },
};
</script>
