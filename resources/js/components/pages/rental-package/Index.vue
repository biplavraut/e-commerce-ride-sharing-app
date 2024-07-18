<template>
  <app-card title="All Rental <b>Packages</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="rental-package.create">Add New</app-btn-link>
      <app-btn
        background="warning"
        icon="cloud_upload"
        data-toggle="modal"
        data-target="#import"
        >Import Excel
      </app-btn>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/rental-package/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>{{ row.duration }}</td>
        <td>{{ row.distance }}</td>
        <td>
          <ul>
            <li v-for="(vehicle, index) in row.vehicles" :key="index">
              <code
                >Vehicle : <b>{{ vehicle.name }}</b> || Price :
                <b>{{ vehicle.price | commaNumberFormat }}</b>
                <span v-if="vehicle.description">|| Desc :</span>
                <b v-if="vehicle.description">{{
                  vehicle.description.substring(0, 15) + "..."
                }}</b>
              </code>
            </li>
          </ul>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'rental-package.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
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
            <h4 class="modal-title">Import Rental Package Excel</h4>
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
import RentalPackage from "@utils/models/RentalPackage";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "RentalPackageIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Name", "Duration", "Distance", "Vehicles", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new RentalPackage(),
      file: "",
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    submitFile() {
      let formData = new FormData();
      formData.append("import_file", this.file);

      axios
        .post(this.model.indexUrl + "/excel-import", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function () {
          alertMessage("Rental Package Imported successfully.");
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
      location.href = baseURL + "Rental Package Import.xlsx";
    },
  },

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped>
</style>