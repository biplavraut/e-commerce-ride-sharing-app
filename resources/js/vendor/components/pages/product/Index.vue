<template>
  <app-card title="All <b>Products</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="product.create">Add New</app-btn-link>
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
      :searchUrl="'/vendor/product/get-products?name='"
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
          {{ row.title }} <small>( code: {{ row.code }})</small>
        </td>
        <td>
          {{ row.category.name }} (
          <small
            >{{ row.subCategory ? row.subCategory.name + " >> " : "" }}
            {{ row.subChildCategory ? row.subChildCategory.name : "" }}</small
          >
          )
          <code v-if="row.update.id">under approval</code>
        </td>
        <td>{{ row.price }}</td>
        <td>
          <span class="badge">{{ row.verified }}</span>
        </td>
        <td>
          <span class="badge badge-success">{{ row.hide }}</span>
        </td>
        <td width="100">
          <!-- <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'product.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions> -->
          -
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

export default {
  name: "VendorProductIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "Category", "Price", "Verifed", "Hidden"],
      rows: { data: [], links: {}, meta: {} },
      model: new Product(),
      file: "",
      type: "",
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

      axios
        .post(this.model.indexUrl + "/excel-import", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
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
    downloadSample() {
      let baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Vendor Product Import.xlsx";
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
  },

  mounted() {
    this.getModels();
  },
  created() {},
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
