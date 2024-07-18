<template>
  <app-card title="All <b>Refundable Order</b>" body-padding="0">
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
          <tr v-for="(item, index) in data" :key="index">
            <td>{{ ++index }}</td>
            <td class="cursor">
              <router-link
              :to="{
                name: 'order.detail',
                params: { id: item.id ,},
              }"
              title="View Detail"
            >
                <span class="badge" title="Order Num">{{
                  item.orderNo
                }}</span
                ><br />
                <span class="badge" title="Order Ref">{{
                  item.refNum
                }}</span
                ><br />
                <span class="badge" title="Order Status">{{
                  item.status
                }}</span>
              </router-link>
            </td>
            <td>
              {{ item.vendor.business_name }}
              <br />
              {{ item.vendor.phone }} / {{ item.vendor.email }}
            </td>
            <td>
              {{ item.user.first_name }} {{ item.user.last_name }}
              <br />
              {{ item.user.phone }}
            </td>
            <td>{{ item.paymentMode }}</td>
            <td>Rs. {{ item.shippingCharge | commaNumberFormat }}</td>
            <td title="Total Order Amount">Rs. {{ item.total | commaNumberFormat }}</td>
            <td title="Refundable Amount">Rs. {{ item.refundAmt | commaNumberFormat }}</td>
            <td>
              <button
                @click.prevent="settle(item.id)"
                title="Settle Now"
                class="btn btn-warning btn-ajax btn-link"
              >
                <i class="material-icons">done </i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </app-card>
</template>

<script>
import moment from "moment";

export default {
  name: "RefundIndex",

  data() {
    return {
      idx: "",
      data: [],
      columns: [
        "S.N",
        "Order",
        "Vendor",
        "User",
        "Payment Mode",
        "Shipping",
        "Total",
        "Refundable Amount",
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
      axios.get("/admin/refund-settlement").then((response) => {
        this.data = response.data.data;
      });
    },
    settle(orderId) {
      swal({
        text: "Explain how did we settled?",
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
            .post("/admin/refund-settlement/update", {
              order: orderId,
              log: log,
            })
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
