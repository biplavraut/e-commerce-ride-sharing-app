<template>
  <app-card title="All <b>Vendors to Settle</b>" body-padding="0">
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
          <tr
            v-for="(item, index) in data"
            :key="index"
            v-show="totalAmount(item) > 0"
          >
            <td>{{ ++index }}</td>
            <td>
              {{ item.businessName }}
              <span title="Full Name">({{ item.fullName }})</span>
              <br />
              <span class="badge">{{ item.phone }}</span> /
              <span class="badge">{{ item.email }}</span>
            </td>
            <td>
              <span class="badge" :title="item.fromTo[0]">{{
                item.fromTo[0]
              }}</span>
              -
              <span class="badge" :title="item.fromTo[1]">{{
                item.fromTo[1]
              }}</span>
              <br />
              {{ item.settlementTime }} Days
            </td>
            <td>
              {{ item.count }}
            </td>
            <td title="Total COD Order Amount">Rs. {{ item.orderTotalCOD }}</td>
            <td title="Total DIGITAl Order Amount">
              Rs. {{ item.orderTotalDIGITAL }}
            </td>
            <td title="Total gogoWallet Order Amount">
              Rs. {{ item.orderTotalWallet }}
            </td>
            <td title="WholeTotal">
              Rs.
              {{ totalAmount(item) }}
            </td>
            <td v-if="item.lastSettledOn">
              {{ formateDate(item.lastSettledOn) }}
            </td>
            <td v-else>-</td>
            <td v-if="totalAmount(item) > 0">
              <button
                @click.prevent="settle(item.id)"
                title="Settle Now"
                class="btn btn-warning btn-ajax btn-link"
              >
                <i class="material-icons">done </i>
              </button>
            </td>
            <td v-else>-</td>
          </tr>
        </tbody>
      </table>
    </div>
  </app-card>
</template>

<script>
import moment from "moment";

export default {
  name: "VendorSettlement",

  data() {
    return {
      idx: "",
      data: [],
      columns: [
        "S.N",
        "Vendor",
        "Settle Range",
        "Total Orders",
        "COD",
        "Digital Wallet",
        "gogoWallet",
        "Total",
        "Last Settled On",
        "Action",
      ],
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    getData() {
      this.data = [];
      axios.get("/admin/vendor-settle").then((response) => {
        this.data = response.data.data;
      });
    },
    settle(vendorId) {
      swal({
        text: "Explain how did we settled?",
        content: "input",
        button: {
          text: "Submit!",
          closeModal: false,
        },
      }).then((log) => {
        if (!log) {
          throw null;
        } else {
          axios
            .post("/admin/vendor-settle/update", { vendor: vendorId, log: log })
            .then((response) => {
              if (response.data.status) {
                alertMessage(response.data.message);
                this.getData();
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
    totalAmount(item) {
      return (
        item.orderTotalCOD + item.orderTotalDIGITAL + item.orderTotalWallet
      );
    },
  },
  created() {
    this.getData();
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
