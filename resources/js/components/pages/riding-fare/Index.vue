<template>
  <app-card title="All Riding <b>Fares</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="riding-fare.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/riding-fare/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.vehicle }}</td>
        <td>{{ row.flatPrice }}</td>
        <td>{{ row.nightSurge ? row.nightSurge : "-" }}</td>
        <td>{{ row.price }}</td>
        <td>
          <span class="badge">{{ row.timeSurges }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'riding-fare.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import RidingFare from "@utils/models/RidingFare";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "RidingFareIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Vehicle",
        "Flat Price",
        "Night Surge",
        "Price (per km)",
        "Time Surges Count",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new RidingFare(),
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
};
</script>

<style scoped>
</style>