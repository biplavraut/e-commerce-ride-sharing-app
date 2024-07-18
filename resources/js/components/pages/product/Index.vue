<template>
  <app-card title="All <b>Products</b> need to Verify" body-padding="0">
    <button
      class="btn btn-round btn-xs title-right-action"
      @click="reset"
      :style="!active ? 'background-color:green;' : ''"
      title="Refresh"
    >
      <i class="material-icons">refresh</i>
      Unverified Only
    </button>

    <button
      class="btn btn-round btn-xs title-right-action"
      @click="verifiedOnly"
      title="Verified Only"
      :style="active === 'verified' ? 'background-color:green;' : ''"
    >
      <i class="material-icons">done_all</i>
      Verified Only
    </button>

    <button
      class="btn btn-round btn-xs title-right-action"
      @click="updateDataList"
      title="Product Update Compare"
      :style="active === 'approve' ? 'background-color:green;' : ''"
    >
      <i class="material-icons">fact_check</i>
      Edited Product Approval
      <sup
        ><span class="badge">{{ updateCount }}</span></sup
      >
    </button>

    <template slot="actions" v-if="!updateEnable">
      <app-btn-link route-name="product.create">Add New</app-btn-link>
    </template>
    <template slot="actions" v-if="!updateEnable">
      <!-- <app-btn-link route-name="product.create">Add New</app-btn-link> -->
      <!-- <app-btn background="info" @click.prevent="exportSheet" icon="archive">Download Excel</app-btn> -->
      <app-btn
        background="warning"
        icon="cloud_upload"
        data-toggle="modal"
        data-target="#import"
        >Import Excel</app-btn
      >
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/product/get-products?name='"
      :word="keyword"
      :searchHolder="'Search By (Product Title, Vendor Phone)'"
      v-if="!updateEnable"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image50"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td
          @click="copy(row.title)"
          style="cursor: pointer"
          title="Click to copy"
        >
          {{ row.title }}
        </td>
        <td>
          {{ row.category.name }} (
          <small
            >{{ row.subCategory ? row.subCategory.name + " >> " : "" }}
            {{ row.subChildCategory ? row.subChildCategory.name : "" }}</small
          >
          )
        </td>
        <td>
          {{ row.vendor ? row.vendor.business_name : "" }}
          <small>({{ row.vendor ? row.vendor.phone : "" }})</small>
        </td>
        <td>{{ row.price }}</td>

        <td>
          <div class="col-md-3">
            <button
              type="button"
              title="Verified"
              class="btn btn-success btn-ajax"
              v-if="row.verified"
            >
              <i class="material-icons">done_all</i>
            </button>
            <button
              type="button"
              title="Verify Now"
              class="btn btn-warning btn-ajax"
              @click="verifyProduct(row)"
              v-else
            >
              <i class="material-icons">done</i>
            </button>
          </div>
          <div class="col-md-3">
            <app-actions
              :actions="{
                edit: { name: 'product.edit', params: { id: row.id } },
              }"
            ></app-actions>
          </div>
          <div class="col-md-3" v-if="authUser.type === 'admin' || authUser.type === 'superadmin' || authUser.type === 'unit-head'">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
          <div class="col-md-1" v-if="!row.verified">
            <input-checkbox @click="addToVerifyList(row.id)"></input-checkbox>
            <!-- <input
              type="checkbox"
              name="checkbox"
              @click="addToVerifyList(row.id)"
            /> -->
          </div>
        </td>
      </template>
    </app-table-sortable>
    <div
      class="col-md-12"
      style="margin-top: -2%"
      v-if="multiple.length > 0"
      title="Check to verify"
    >
      <app-btn background="warning" @click.prevent="verifyAll" icon="done_all"
        >Verify Selected</app-btn
      >
    </div>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      v-if="updateEnable"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.product.image50"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td
          @click="copy(row.product.title)"
          style="cursor: pointer"
          title="Click to copy"
        >
          {{ row.product.title }}
        </td>

        <td>
          {{ row.product.category.name }} (
          <small
            >{{
              row.product.subCategory
                ? row.product.subCategory.name + " >> "
                : ""
            }}
            {{
              row.product.subChildCategory
                ? row.product.subChildCategory.name
                : ""
            }}</small
          >
          )
        </td>
        <td>
          {{ row.product.vendor.business_name }}
          <small>({{ row.product.vendor.phone }})</small>
        </td>
        <td>{{ row.product.price }}</td>
        <td width="100">
          <button type="button" class="btn btn-success btn-ajax">
            <router-link
              :to="{
                name: 'product.compare',
                params: { idx: row.product.id },
              }"
              title="Click to compare"
            >
              <i class="material-icons">fact_check</i>
            </router-link>
          </button>
        </td>
      </template>
    </app-table-sortable>

    <!-- Excel Import -->
    <div class="modal fade" id="import" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Import Product Excel</h4>
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
              @click="downloadSample()"
            >
              Download Sample
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
import Product from "@utils/models/Product";
import { index, destroy } from "@utils/mixins/Crud";
import { mapMutations } from "vuex";
import { mapGetters } from "vuex";

export default {
  name: "ProductIndex",

  mixins: [index, destroy],

  data() {
    return {
      active: "",
      columns: ["Image", "Title", "Category", "Vendor", "Price"],
      rows: { data: [], links: {}, meta: {} },
      model: new Product(),
      file: "",
      type: "",
      keyword: "",
      filteringProduct: [],
      isLoading: false,
      multiple: [],
      keyword: "",
      updateProductList: [],
      updateCount: 0,
      updateEnable: false,
    };
  },
  methods: {
    ...mapMutations(["updateThisMonthProductsCount"]),

    exportSheet() {
      if (confirm("Are you sure?"))
        window.location = this.model.indexUrl + "/excel-export";
    },
    submitFile() {
      let formData = new FormData();
      formData.append("import_file", this.file);
      formData.append("type", this.type);

      if (!this.file) {
        alertMessage("Please select excel file to import.", "danger");
        return;
      }

      var fileReader = new FileReader();
      fileReader.onload = function (event) {
        var data = event.target.result;

        var workbook = XLSX.read(data, {
          type: "binary",
        });
        workbook.SheetNames.forEach((sheet) => {
          let rowObject = XLSX.utils.sheet_to_row_object_array(
            workbook.Sheets[sheet]
          );
          let jsonObject = JSON.stringify(rowObject);

          var parser = JSON.parse(jsonObject);

          if (parser.length > 0) {
            axios
              .post("/admin/product/excel-import", { data: parser })
              .then((response) => {
                if (response.data.message == "success") {
                  swal({
                    title: "Good Job!",
                    text: "File has been imported successfully.",
                    icon: "success",
                  });
                  location.reload();
                } else {
                  swal({
                    title: "Awww!",
                    text: response.data.message,
                    icon: "error",
                  });
                }
              })
              .catch(function () {
                console.log("FAILURE!!");
                swal({
                  title: "Awww!",
                  text: "File cannot be imported.",
                  icon: "error",
                });
              });
          }

          // console.log(parser, parser.length);
        });
      };
      fileReader.readAsBinaryString(this.file);

      // axios
      //   .post(this.model.indexUrl + "/excel-import", formData, {
      //     headers: {
      //       "Content-Type": "multipart/form-data",
      //     },
      //   })
      //   .then((response) => {
      //     if (response.data.message == "success") {
      //       swal({
      //         title: "Good Job!",
      //         text: "File has been imported successfully.",
      //         icon: "success",
      //       });
      //       location.reload();
      //     } else {
      //       swal({
      //         title: "Awww!",
      //         text: response.data.message,
      //         icon: "error",
      //       });
      //     }
      //   })
      //   .catch(function () {
      //     console.log("FAILURE!!");
      //     swal({
      //       title: "Awww!",
      //       text: "File cannot be imported.",
      //       icon: "error",
      //     });
      //   });

      this.otherFields();
    },

    otherFields() {
      $("#import").modal("hide");

      this.getModels();
    },
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    downloadSample() {
      let baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Product Import.xlsx";
    },
    async verifyProduct(row) {
      let response = await this.model.verify(row.id);

      if (response === "success") {
        row.verified = true;
        this.rows.data.splice(this.rows.data.indexOf(row), 1);
        alertMessage("Product Verified");
      } else {
        alertMessage(response, "danger");
      }
    },
    addToVerifyList(productId) {
      const exist = this.multiple.includes(productId);
      if (!exist) {
        this.multiple.push(productId);
      } else {
        this.multiple = this.multiple.filter((item) => {
          return item !== productId;
        });
      }
    },
    async verifyAll() {
      let response = await this.model.verifyMultiple(this.multiple);
      if (response === "success") {
        this.rows = { data: [], links: {}, meta: {} };
        this.multiple = [];
        this.reset();
        alertMessage("Products Verified");
      }
    },
    async reset() {
      this.updateEnable = false;
      this.active = "";
      let products = await this.model.reset();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = products.data;
      this.rows.links = products.links;
      this.rows.meta = products.meta;
    },
    async verifiedOnly() {
      this.active = "verified";
      this.updateEnable = false;
      let products = await this.model.verifiedProducts();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = products.data;
      this.rows.links = products.links;
      this.rows.meta = products.meta;
    },
    copy(name) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = name;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);

      alertMessage("Title copied to clipboard.");
    },
    updateList() {
      axios.get(this.model.indexUrl + "/update-list").then((response) => {
        this.updateProductList = response.data;
        this.updateCount = response.data.meta.total;
      });
    },
    updateDataList() {
      this.updateEnable = true;
      this.active = "approve";
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = this.updateProductList.data;
      this.rows.links = this.updateProductList.links;
      this.rows.meta = this.updateProductList.meta;
    },
  },

  mounted() {
    // this.getModels();
    // this.updateList();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
  created() {
    if (this.$route.params.phone) {
      this.keyword = this.$route.params.phone;
    }else{
      this.getModels();
      this.updateList();
    }
  },
  watch: {},
};
</script>

<style scoped>
.card-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /* background-color : #337AB7; */
  color: #666666;
}
</style>