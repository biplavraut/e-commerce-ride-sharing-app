<template>
  <app-card title="Discount <b>Details</b>" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows">
      <template slot-scope="{ row }">
        <td>{{ row.discountType }}</td>
        <td>{{ row.discountValue }}</td>
        <td>{{ row.appliedFrom }}</td>
        <td>{{ row.appliedTill }}</td>
        <td>{{ row.status == true ? "active" : "inactive" }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'discount.edit', params: { id: row.id } },
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Discount from "@utils/models/Discount";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "DiscountIndex",
  mixins: [index, destroy],
  data() {
    return {
      columns: [
        "Discount Type",
        "Discount Value",
        "Apply Date",
        "End Date",
        "Status",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Discount(),
    };
  },

  methods: {},

  computed: {
    ...mapGetters(["authUser"]),
  },

  mounted() {
    this.getModels();
  },
};
</script>
<style scoped>
</style>