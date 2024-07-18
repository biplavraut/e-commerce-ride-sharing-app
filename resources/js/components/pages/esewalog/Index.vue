<template>
  <app-card title="<b>Esewa Log</b>" body-padding="0">
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
            <td>{{ data.transactionDetails.referenceId }}</td>
            <td>Rs. {{ data.totalAmount }}</td>
            <td>{{ data.productName }}</td>
            <td>{{ data.transactionDetails.status }}</td>
            <td>{{ data.transactionDetails.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </app-card>
</template>

<script>
import moment from "moment";
import Esewa from "@utils/models/Esewa";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "EsewaLogIndex",

  mixins: [index, destroy],

  data() {
    return {
      idx: "",
      data: null,
      columns: ["Idx", "Amt", "Product", "Status", "Added On"],
      model: new Esewa(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    getData() {
      axios.get(this.model.indexUrl + "?idx=" + this.idx).then((response) => {
        this.data = response.data[0];
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
