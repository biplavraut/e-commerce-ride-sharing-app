<template>
  <app-card title="<b>Khalti Log</b>" body-padding="0">
    <!-- <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="false"
      :searchUrl="'/admin/khalti-log?idx='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.idx }}</td>
        <td>{{ row.type.name }}</td>
        <td>{{ row.amount / 100 }}</td>
        <td>{{ row.user.name }}</td>
        <td>{{ row.meta.product_name }}</td>
        <td>{{ row.created_on }}</td>
      </template>
    </app-table-sortable> -->

    <div class="table-responsive" style="overflow: scroll !important">
      <table class="table" style="margin-bottom: 0">
        <thead>
          <tr>
            <th v-for="(column, index) in columns" :key="index">
              {{ column }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="data">
            <td>{{ data.idx }}</td>
            <td>{{ data.type.name }}</td>
            <td>Rs. {{ data.amount / 100 }}</td>
            <td>{{ data.user.name }}</td>
            <td>{{ data.meta.product_name }}</td>
            <td>{{ data.can_refund ? "Yes" : "No" }}</td>
            <td>{{ data.state.name }}</td>
            <td>{{ formatDate(data.created_on) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </app-card>
</template>

<script>
import moment from "moment";
import Khalti from "@utils/models/Khalti";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "KhaltiLogIndex",

  mixins: [index, destroy],

  data() {
    return {
      idx: "",
      data: null,
      columns: [
        "Idx",
        "Type",
        "Amt",
        "User",
        "Product",
        "Refundable",
        "Status",
        "Added On",
      ],
      model: new Khalti(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    getData() {
      axios.get(this.model.indexUrl + "?idx=" + this.idx).then((response) => {
        this.data = response.data;
      });
    },
  },
  created() {
    if (this.$route.params.idx) {
      this.idx = this.$route.params.idx;
      this.getData();
    }
  },
  mounted() {},
  computed: {},
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
