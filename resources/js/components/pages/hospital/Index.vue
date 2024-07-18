<template>
  <app-card title="All <b>Hospitals</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="hospital.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/hospital/get-data?name='"
      :searchHolder="'Search (By Hospital)'"
    >
      <template slot-scope="{ row }">
        <td>{{ row.title }}</td>
        <td>Lat: {{ row.lat }} <br>Long: {{ row.long }}</td>
        <td> 
          <!-- {{ JSON.parse(row.vendors).length }} -->
          <div v-for="(vendor, index) in row.vendors" :key="index" > 
              <span class="badge"> {{ vendor.name }} </span>
          </div>
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
                name: 'hospital.edit',
                params: { id: row.id },
              },
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Hospital from "@utils/models/Hospital";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "HospitalIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Hospital", "Location", "Vendors", "Created At"],
      rows: { data: [], links: {}, meta: {} },
      model: new Hospital(),
      file: "",
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
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