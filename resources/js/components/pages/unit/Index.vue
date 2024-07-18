<template>
  <app-card title="All <b>Units</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="unit.create">Add New</app-btn-link>
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
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/unit/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>{{ row.category.name }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'unit.edit', params: { id: row.id } },
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
            <h4 class="modal-title">Import Unit Excel</h4>
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
import Unit from "@utils/models/Unit";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "UnitIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Name", "Category"],
      rows: { data: [], links: {}, meta: {} },
      model: new Unit(),
      file: "",
    };
  },

  methods: {
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
          alertMessage("Unit Imported successfully.");
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
      location.href = baseURL + "Unit Import.xlsx";
    },
  },

  mounted() {
    this.getModels();
  },
};
</script>
