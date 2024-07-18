<template>
  <app-card
    :title="
      edit
        ? 'Edit ' + form.title
        : ' Add New Product ' + '<b>' + form.title + '</b>'
    "
  >
    <form @submit.prevent="saveData">
      <div class="col-md-12">
        <ul class="nav nav-pills nav-pills-warning">
          <li class="active">
            <a href="#detail" data-toggle="tab" aria-expanded="true">Detail</a>
          </li>
          <li class>
            <a href="#options" data-toggle="tab" aria-expanded="false"
              >Options</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="detail">
            <input-images
              v-model="form.images"
              :image-url="imageUrl"
              required
              :pimages="images"
              :label="'Image (1100px * 1100px)'"
              title="1100px * 1100px"
              :error-text="errors.first('images')"
            ></input-images>

            <small
              class="text-center text-danger"
              v-if="form.errors.has('images')"
              >* {{ form.errors.get("images") }}</small
            >

            <div class="row">
              <div class="col-md-12">
                <div class="form-group asdh-select" name="role">
                  <label>Vendor</label>
                  <select class="form-control" v-model="form.vendorId">
                    <option value disabled>Select Vendor</option>
                    <option
                      data-tokens=""
                      :value="option.id"
                      v-for="(option, index) in vendors"
                      :key="index"
                    >
                      {{ option.name }}
                    </option>
                  </select>
                </div>
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
              <div class="col-md-12"></div>
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
                  <label>Badges</label>
                  <input-tags
                    element-id="badges"
                    v-model="form.badge"
                    :typeahead="false"
                  ></input-tags>
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
              <div class="col-md-12">
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
              <div class="col-md-6">
                <input-text
                  label="Batch No"
                  type="number"
                  name="batch_no"
                  v-model="form.batchNo"
                ></input-text>
              </div>
              <div class="col-md-6">
                <input-text
                  label="Expiration Date"
                  type="date"
                  name="expire_date"
                  v-model="form.expireDate"
                ></input-text>
              </div>
              <!-- <div class="col-md-4">
                <input-text
                  label="Unit"
                  type="text"
                  name="unit"
                  v-model="form.unit"
                ></input-text>
              </div> -->
            </div>

            <div class="row">
              <div class="row">
                <div class="col-md-10">
                  <h3 class="my-sub-heading">Price Related</h3>
                </div>
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
                  label="Gogo's Price"
                  type="text"
                  name="price1"
                  v-model="form.price1"
                  v-validate="'required'"
                  :error-text="errors.first('price1')"
                  required
                ></input-text>
              </div>
              <div class="col-md-4">
                <div class="form-group asdh-select" name="role">
                  <label>Pricing Unit</label>
                  <select class="form-control" v-model="form.unit">
                    <option value disabled>Select Unit</option>
                    <option
                      data-tokens=""
                      :value="item.value"
                      v-for="(item, index) in myUnits"
                      :key="index"
                    >
                      {{ item.name }}
                    </option>
                  </select>
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
                  label="Discount"
                  name="discount"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="Elite Member Discount Percent"
                  name="elite_percent"
                  v-model="form.elitePercent"
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
          </div>

          <div class="tab-pane" id="options">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group asdh-select" name="role">
                  <label>Vendor</label>
                  <select class="form-control" v-model="form.vendorId">
                    <option value disabled>Select Vendor</option>
                    <option
                      data-tokens=""
                      :value="option.id"
                      v-for="(option, index) in vendors"
                      :key="index"
                    >
                      {{ option.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div
                class="col-md-2"
                v-for="item in form.productOptionCategories"
                :key="item.id"
              >
                <input-checkbox
                  :label="item.name"
                  v-model="item.status"
                ></input-checkbox>
              </div>
            </div>
            <br />
            <div class="row" style="background: #e1e1e1; border-radius: 20px">
              <center><span class="text-muted">Advance Control</span></center>
              <br />
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    <input-checkbox
                      label="Hide"
                      v-model="form.hide"
                    ></input-checkbox>
                  </div>
                  <div class="col-md-2">
                    <input-checkbox
                      label="Is Default"
                      v-model="form.isDefault"
                    ></input-checkbox>
                  </div>
                </div>              
                <hr>
                <center><span class="text-muted">Return Settings</span></center>
                <div class="row">
                  <div class="col-md-2">
                    <input-checkbox
                      label="Return"
                      v-model="form.isReturn"
                    ></input-checkbox>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group asdh-select" name="role">
                      <label>Return Days</label>
                      <select class="form-control" v-model="form.returnDays">
                        <option value disabled>Select Days</option>
                        <option
                          data-tokens=""
                          :value="item.value"
                          v-for="(item, index) in returnDays"
                          :key="index"
                        >
                          {{ item.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>

      <div class="text-right">
        <button type="submit" :disabled="errors.any()" class="btn btn-success">
          {{ edit ? "Update" : "Save" }}
        </button>
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
  name: "ProductCreate",

  mixins: [store, save],

  data() {
    return {
      form: new Form({
        productCategoryId: "",
        vendorId: "",
        title: "",
        slug: "",
        code: "",
        badge: "",
        price: "",
        elitePercent: 0,
        price1: "",
        hide: false,
        isDefault: false,
        openingStock: 0,
        description: "",
        discountType: "amount",
        discount: 0,
        verified: 0,
        size: "",
        color: "",
        prescriptionRequired: false,
        batchNo: "",
        expireDate: "",
        unit: "",
        vatPercentage: 0,
        serviceChargePercentage: 0,
        images: "",
        tags: [],
        productOptionCategories: [],
        isReturn:false,
        returnDays:""
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
      imageUrl: Helpers.cameraImage(),
      model: new Product(),
      categories: [],
      subCategories: [],
      subChildCategories: [],
      productOptionCategories: [],
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
      returnDays: [
        {
          value: "7",
          name: "7 Days",
          selected:'selected'
        },
        {
          value: "15",
          name: "15 Days",
          selected:''
        },
        {
          value: "30",
          name: "30 Days",
          selected:''
        },
      ],
      myUnits: [
        {
          value: "item",
          name: "Item",
          selected:'selected'
        },
        {
          value: "hourly",
          name: "Hourly",
          selected:''
        },
        {
          value: "daily",
          name: "Daily",
          selected:''
        },
        {
          value: "weekly",
          name: "Weekly",
          selected:''
        },
        {
          value: "monthly",
          name: "Monthly",
          selected:''
        },
        {
          value: "yearly",
          name: "Yearly",
          selected:''
        },
      ],
      images: [],
      subCategoryId: "",
      subChildCategoryId: "",
      vendor: new Vendor(),
      vendors: [],
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();

      if (this.form.price1 === 0 || this.form.price1 == "") {
        alertMessage("Please add gogoPrice to store this product.", "danger");
        return;
      }
      if (parseFloat(this.form.price1) > parseFloat(this.form.price)) {
        alertMessage("gogoPrice is greater then Marked Price.", "danger");
        return;
      }

      if (this.subCategoryId) {
        this.form.productCategoryId = this.subCategoryId;
      }
      if (this.subChildCategoryId) {
        this.form.productCategoryId = this.subChildCategoryId;
      }

      if (this.form.productCategoryId === "") {
        alertMessage(
          "Please select product category to store this product.",
          "danger"
        );
        return;
      }

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `product.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
        if (this.form.errors.has("image")) Helpers.focusId("image");
        if (this.form.errors.has("icon")) Helpers.focusId("icon");
      }
    },

    async updateData() {
      if (this.form.price1 === 0) {
        alertMessage("Please add gogoPrice to update this product.", "danger");
        return;
      }

      if (this.subCategoryId) {
        this.form.productCategoryId = this.subCategoryId;
      }
      if (this.subChildCategoryId) {
        this.form.productCategoryId = this.subChildCategoryId;
      }

      if (this.form.productCategoryId === "") {
        alertMessage(
          "Please select product category to update this product.",
          "danger"
        );
        return;
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
          name: `product.index`,
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
        elitePercent,
        images,
        category,
        subCategory,
        subChildCategory,
        openingStock,
        code,
        discountType,
        discount,
        size,
        color,
        prescriptionRequired,
        batchNo,
        expireDate,
        unit,
        tags,
        productOptionCategories,
        hide,
        isDefault,
        vendor,
        serviceChargePercentage,
        vatPercentage,
        isReturn,
        returnDays,
      } = data.data;
      this.form = new Form({
        title,
        slug,
        price,
        elitePercent,
        price1,
        badge,
        description,
        images: "",
        productCategoryId: category.id,
        openingStock,
        code,
        discountType,
        discount,
        size,
        color,
        prescriptionRequired,
        batchNo,
        expireDate,
        unit,
        tags,
        productOptionCategories,
        hide,
        isDefault,
        vendorId: vendor.id,
        serviceChargePercentage,
        vatPercentage,
        isReturn,
        returnDays,
      });
      this.images = images;
      this.subCategoryId = subCategory.id;
      this.subChildCategoryId = subChildCategory.id;

      this.getProductOptions(vendor.id);

      if (productOptionCategories.length === 0) {
        this.form.productOptionCategories = this.productOptionCategories;
      } else {
        this.form.productOptionCategories = productOptionCategories[0].map(
          (optionCategory) => {
            return {
              id: optionCategory.id,
              name: optionCategory.title,
              status: optionCategory.status,
            };
          }
        );
        // const optionList = this.form.productOptionCategories.concat(
        //   this.productOptionCategories
        // );
        // let set = new Set();
        // let unionArray = optionList.filter((item) => {
        //   if (!set.has(item.id)) {
        //     set.add(item.id);
        //     return true;
        //   }
        //   return false;
        // }, set);
        // this.form.productOptionCategories = unionArray;
      }
    },

    async getVendorList() {
      let vendors = await this.vendor.getList();
      this.vendors = vendors.data.map((vendor) => {
        return {
          id: vendor.id,
          name: vendor.businessName,
        };
      });
    },

    async getCategories(vendor = "") {
      let categories = await this.model.getCategory(vendor);
      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name + " (" + category.slug + ")",
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
    async getProductOptions(val = "") {
      let productOptionCategories = await this.model.getProductOptions(val);
      this.productOptionCategories = productOptionCategories.data.map(
        (item) => {
          return {
            id: item.id,
            name: item.title,
            status: false,
          };
        }
      );
      if (!this.edit) {
        this.form.productOptionCategories = productOptionCategories.data.map(
          (optionCategory) => {
            return {
              id: optionCategory.id,
              name: optionCategory.title,
              status: false,
            };
          }
        );
      }
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
    productOptionCategoriesUnion() {
      const optionList = this.form.productOptionCategories.concat(
        this.productOptionCategories
      );
      let set = new Set();
      let unionArray = optionList.filter((item) => {
        if (!set.has(item.id)) {
          set.add(item.id);
          return true;
        }
        return false;
      }, set);
      this.form.productOptionCategories = unionArray;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    this.getVendorList();

    this.getCategories();
    this.getTags();

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();
    }
  },

  computed: {
    ...mapGetters(["authUser"]),
    randomCode() {
      return this.form.slug + "-" + Math.floor(Math.random() * 999 + 111);
    },
  },
  watch: {
    "form.title": function (val) {
      if (!this.edit || this.form.code.length < 1) {
        this.form.slug = "gogo20" + "-" + this.form.vendorId + "-" + val.slug();
        this.form.code = this.randomCode;
      }
    },
    "form.productCategoryId": function (val) {
      if (this.edit) {
        this.getSubCategory(val);
      }
      // this.getUnits(val);
    },
    subCategoryId: function (val) {
      if (this.edit) {
        this.getSubChildCategory(val);
      }
      if (val) {
        // this.getUnits(val);
      }
    },
    subChildCategoryId: function (val) {
      if (val) {
        // this.getUnits(val);
      }
    },
    "form.vendorId": function (val) {
      this.getProductOptions(val);
      this.getCategories(val);
    },
    productOptionCategories: function (val) {
      if (this.edit) {
        this.productOptionCategoriesUnion();
      }
    },
  },
};
</script>
