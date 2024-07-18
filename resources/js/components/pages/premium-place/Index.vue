<template>
  <app-card title="All <b>Places</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="premium-place.create">Add New</app-btn-link>

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
      :searchUrl="'/admin/premium-place/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image50"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.location }}</td>
        <td>{{ row.lat }}</td>
        <td>{{ row.long }}</td>
        <td>{{ row.outstationPrice | commaNumberFormat }}</td>
        <td>{{ row.price }}</td>
        <td>{{ row.radius }}</td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td>
          <span class="badge">{{ row.popular ? "YES" : "NO" }}</span>
        </td>
        <td>{{ formatDate(row.createdAt) }}</td>

        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'premium-place.edit',
                params: { id: row.id },
              },
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
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
            <h4 class="modal-title">Import Premium Place Excel</h4>
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
import PremiumPlace from "@utils/models/PremiumPlace";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "PremiumPlaceIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Image",
        "Location",
        "Latitude",
        "Longitude",
        "Charge",
        "Surge",
        "Radius",
        "Hidden",
        "Outstation Destination",
        "Created At",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new PremiumPlace(),
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
          alertMessage("Premium Place Imported successfully.");
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
      location.href = baseURL + "Premium Place Import.xlsx";
    },
  },

  mounted() {
    this.getModels();
  },
  computed: {
    ...mapGetters(["authUser"]),
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