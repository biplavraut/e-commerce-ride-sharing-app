<template>
  <app-card title="All <b>Utility Promo Codes</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="utility-coupon.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/utility-coupon/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.code }}</td>
        <td>{{ row.amount }}</td>
        <td>{{ row.usedTimes }}</td>
        <td>{{ row.users }}</td>
        <td>{{ formatValidDate(row.validTill) }}</td>
        <td>{{ row.createdAt }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'utility-coupon.edit', params: { id: row.id } },
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
import UtilityCoupon from "@utils/models/UtilityCoupon";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "UtilityCouponIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Code",
        "Amount (in Rs.)",
        "Used Times",
        "No of Users",
        "Valid Till",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new UtilityCoupon(),
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