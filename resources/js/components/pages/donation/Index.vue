<template>
  <app-card title="All <b>Donations</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="donation.create">Add New</app-btn-link>
    </template>
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/donation/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td :title="row.user.country_code + ' ' + row.user.phone">
          <small
            >{{ row.user.first_name }} {{ row.user.last_name }} <br />
            <small class="badge">{{ row.user.phone }}</small></small
          >
        </td>
        <td>{{ row.trust }}</td>
        <td>{{ row.donation }}</td>
        <td>{{ row.createdAt }}</td>
        <td width="100">-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Donation from "@utils/models/Donation";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "DonationIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["User", "Trust", "Amount (in Rs.)", "Received On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Donation(),
    };
  },

  methods: {},

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped>
</style>