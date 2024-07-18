<template>
  <app-card title="All <b>Product Categories</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="product-category.create">Add New</app-btn-link>
      <app-btn background="info" @click.prevent="exportSheet" icon="archive"
        >Download Excel</app-btn
      >
      <app-btn
        background="warning"
        icon="cloud_upload"
        data-toggle="modal"
        data-target="#import"
        >Import Excel
      </app-btn>
    </template>

    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li :class="active == 0 ? 'active' : ''" @click.prevent="reset">
        <a href="#all" data-toggle="tab" aria-expanded="true">All <span class="badge">{{ allCount }}</span></a>
      </li>
      <li
        v-for="(category, index) in services"
        :key="index"
        @click.prevent="fetchCategoryData(category.id)"
        :class="active == category.id ? 'active' : ''"
      >
        <a :href="'#' + category.slug" data-toggle="tab" aria-expanded="true"
          >{{ category.name }}
          <sup>
            <span class="badge">{{ category.categoryCount }}</span>
          </sup>
        </a>
      </li>
    </ul>
    <category-tree
      :items="rows.data"
      style="padding: 0"
      :tab="active"
      @deleteItem="deleteModel"
    ></category-tree>

    <!-- Excel Import -->
    <div class="modal fade" id="import" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Import Category Excel</h4>
          </div>
          <div class="modal-body">
            <input
              type="file"
              id="file"
              name="import_file"
              ref="file"
              class="btn btn-secondary"
              @change="handleFileUpload()"
            />
            <select name="type" v-model="type" class="form-control">
              <option disabled selected>Selct Type of Data</option>
              <option value="category">Category</option>
              <option value="subcategory">Sub Category</option>
            </select>
            <button
              type="submit"
              class="btn btn-round btn-primary"
              @click="submitFile()"
            >
              Import
            </button>
            <button
              type="button"
              class="btn btn-sm btn-round btn-warning"
              @click="downloadCategorySample()"
            >
              Download Category Sample
            </button>

            <button
              type="button"
              class="btn btn-sm btn-round btn-warning"
              @click="downloadSubCategorySample()"
            >
              Download Sub Category Sample
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-card>
</template>

<script>
import Category from "@utils/models/ProductCategory";
import Service from "@utils/models/Category";
import { index, destroy } from "@utils/mixins/Crud";
import CategoryTree from "./CategoryTree";
import { mapMutations } from "vuex";

export default {
  name: "CategoryIndex",

  components: { CategoryTree },

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Name"],
      rows: { data: [], links: {}, meta: {} },
      model: new Category(),
      file: "",
      type: "category",
      categories: [],
      service: new Service(),
      services: [],
      active: 0,
      allCount: 0,
    };
  },

  methods: {
    ...mapMutations(["updateThisMonthCategoriesCount"]),

    exportSheet() {
      if (confirm("Are you sure?"))
        window.location = this.model.indexUrl + "/excel-export";
    },

    submitFile() {
      let formData = new FormData();
      formData.append("import_file", this.file);
      formData.append("type", this.type);

      axios
        .post(this.model.indexUrl + "/excel-import", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function () {
          alertMessage("Product Category Imported successfully.");
        })
        .catch(function () {
          console.log("FAILURE!!");
        });

      this.otherFields();
    },

    otherFields() {
      $("#import").modal("hide");
      this.getModels();
    },

    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    downloadCategorySample() {
      let baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Product Category Import.xlsx";
    },
    downloadSubCategorySample() {
      let baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Product Sub Category Import.xlsx";
    },
    reset() {
      axios.get("/admin/product-category").then((response) => {
        this.active = 0;
        this.rows.data = response.data.data;
      });
    },
    async fetchCategoryData(id) {
      let data = await this.model.getData(id);
      this.rows.data = data.data;
      this.active = id;
    },
    async getCategories() {
      let categories = await this.model.getRoot();

      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name,
          slug: category.slug,
        };
      });
    },
    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        this.allCount += item.categoryCount;
        return {
          id: item.id,
          name: item.name,
          categoryCount: item.categoryCount,
        };
      });
    },
  },

  mounted() {
    this.getModels();
    this.getCategories();
    this.getServices();
  },
  created() {
    if (this.$route.params.active && this.$route.params.active !== 0) {
      this.fetchCategoryData(this.$route.params.active);
    }
  },
};
</script>
