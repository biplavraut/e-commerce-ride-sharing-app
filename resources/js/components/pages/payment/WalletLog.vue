<template>
  <app-card
    title="Wallet Advance Logs"
    body-padding="0"
  >
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/wallet-log/get-data?name='"
      :searchHolder="'Search (By User Name, Phone)'"
      :actions="false"
    >
      <template slot-scope="{ row }">
        <td title="User Details">
          {{ row.user.firstName}} {{ row.user.lastName}}
          <span class="badge cursor" title="Phone">{{
              row.user.phone
            }}</span>
        </td>
        <td>{{ row.user.gogoWallet | commaNumberFormat }}</td>
        <td>{{ row.amount | commaNumberFormat }}</td>
        <td>{{ row.remarks }}</td>
        <td>{{ formatDate(row.createdAt) }}</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import WalletLog from "@utils/models/WalletLog";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "WalletLogIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },

      columns: [
        "User",
        "Total Wallet",
        "Loaded Amount",
        "Remarks",
        "Created At"
      ],
      model: new WalletLog(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    markAsReceived(row) {
      swal({
        text: "If you want to explain?",
        content: "input",
        button: {
          text: "Submit!",
          closeModal: true,
        },
      }).then((log) => {
        if (!log || log.length < 5) {
          return swal("Oh noes!", "Write something more clearly!", "error");
        } else {
          axios
            .post("/admin/wallet-log/receive", {
              id: row.id,
              log: log,
            })
            .then((response) => {
              if (response.data === "success") {
                alertMessage("Operation Success");
                const index = this.rows.data.indexOf(row);
                if (index > -1) {
                  this.rows.data.splice(index, 1);
                }
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
  },
  created() {
    this.getModels();
  },
  mounted() {},
  computed: {
    ...mapGetters(["authUser"]),
  },
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
