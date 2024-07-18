<template>
  <app-card title="All <b>Junctions</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="delivery-junction.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/delivery-junction/get-data?name='"
      :searchHolder="'Search (By Location)'"
    >
      <template slot-scope="{ row }">
        <td>{{ row.location }}</td>
        <td>{{ row.lat }}</td>
        <td>{{ row.long }}</td>
        <td>{{ formatDate(row.createdAt) }}</td>

        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'delivery-junction.edit',
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
import DeliveryJunction from "@utils/models/DeliveryJunction";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "DeliveryJunctionIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Location", "Latitude", "Longitude", "Created At"],
      rows: { data: [], links: {}, meta: {} },
      model: new DeliveryJunction(),
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