<template>
  <app-card title="All <b>Driver Payments</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/payment-settlement/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td :title="row.driver.phone">
          <small
            >{{ row.driver.first_name }} {{ row.driver.last_name }} <br />
            <small class="badge">{{ row.driver.phone }}</small></small
          >
        </td>
        <td
          :class="row.payableAmount >= defaultConf.riderCredit ? 'danger' : ''"
          title="Click to Update"
          style="cursor: pointer"
        >
          Rs. {{ row.payableAmount | commaNumberFormat }}
        </td>
        <!-- <td
          @click.prevent="updateSettlement(row.id, 'rec')"
          title="Click to Update"
          style="cursor: pointer"
        >
          Rs. {{ row.receivableAmount }}
        </td> -->
        <td style="cursor: pointer">Rs. {{ row.donationAmount | commaNumberFormat }}</td>
        <td width="100">
          <button
            type="button"
            :title="row.driver.is_blocked === 1 ? 'UnBlock Now' : 'Block Now'"
            :class="
              row.driver.is_blocked === 1
                ? 'btn btn-success btn-ajax'
                : 'btn btn-danger btn-ajax'
            "
            @click="blockRider(row.driver.id)"
          >
            <i class="material-icons">{{
              row.driver.is_blocked === 1 ? "vpn_key" : "lock"
            }}</i>
          </button>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Payment from "@utils/models/Payment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "PaymentIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Rider", "gogo20's Commission", "Donation Amount to Receive"],
      rows: { data: [], links: {}, meta: {} },
      model: new Payment(),
      payment: { payable: 0, receivable: 0 },
      defaultConf: {},
    };
  },

  methods: {
    updateSettlement(paymentId, type) {
      swal({
        text: "Enter an amount.",
        content: "input",
        button: {
          text: "Update!",
          closeModal: false,
        },
      }).then((amt) => {
        if (amt) {
          if (type === "pay") {
            this.payment.payable = amt;
          } else {
            this.payment.receivable = amt;
          }

          axios
            .put("/admin/payment-settlement/" + paymentId, this.payment)
            .then((response) => {
              alertMessage("Settlement Amount updated.");
              this.reset();
            });
        }
        swal.stopLoading();
        swal.close();
      });
    },
    async reset() {
      this.payment = { payable: 0, receivable: 0 };
      let payments = await this.model.reset();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = payments.data;
      this.rows.links = payments.links;
      this.rows.meta = payments.meta;
    },
    blockRider(riderId) {
      if (confirm("Are you sure? You want to proceed.")) {
        axios
          .post("/admin/driver/clear-block-blacklist", {
            id: riderId,
            type: "block",
          })
          .then((response) => {
            this.reset();
            alertMessage("Operation Success.");
          });
      }
    },
    getDefaultConf() {
      axios.get("/admin/default-conf").then((response) => {
        this.defaultConf = response.data.data;
      });
    },
  },

  mounted() {
    this.getModels();
    this.getDefaultConf();
  },
};
</script>

<style scoped>
</style>