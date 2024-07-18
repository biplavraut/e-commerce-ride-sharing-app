<template>
  <app-card title="All <b>Coupon Codes</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="coupon.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/coupon/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.code }}</td>
        <td>{{ row.amount }}</td>
        <td>{{ row.usedTimes }}</td>
        <td>{{ formatValidDate(row.validTill) }}</td>
        <td>{{ row.createdAt }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'coupon.edit', params: { id: row.id } },
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
import Coupon from "@utils/models/Coupon";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "CouponIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Code",
        "Amount (in Rs.)",
        "Used Times",
        "Valid Till",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Coupon(),
    };
  },

  methods: {
    formatValidDate(date) {
      return moment(date).format("LL");
    },
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
};
</script>

<style scoped>
</style>