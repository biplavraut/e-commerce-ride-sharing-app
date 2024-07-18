<template>
  <app-card title="All <b>Vouchers</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="voucher.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/voucher/get-data?name='"
      searchHolder="Search (By Code)"
    >
      <template slot-scope="{ row }">
        <td @click="copyCode(row.code)" class="cursor">
          <code>{{ row.code }}</code>
        </td>
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <br />
          {{ row.user.phone }}
        </td>
        <td>Rs. {{ row.amount | commaNumberFormat }}</td>
        <td>{{ row.used == 0 ? "Applicable" : "Used" }}</td>
        <td>{{ formatValidDate(row.createdAt) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              delete: row.id,
            }"
          ></app-actions>
          <app-actions v-if="row.used == 0"
            :actions="{
              edit: { name: 'voucher.edit', params: { id: row.id } },
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Voucher from "@utils/models/Voucher";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "VoucherIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Code", "User", "Amount", "Status","Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Voucher(),
    };
  },

  methods: {
    formatValidDate(date) {
      return moment(date).format("LLLL");
    },
    copyCode(code) {
      return copyContent(code, "Voucher code copied.");
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
.cursor {
  cursor: pointer;
}
</style>
