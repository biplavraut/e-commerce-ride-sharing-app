<template>
  <app-card title="All <b>Payment Logs</b>" body-padding="0">
    <div class="row">
      <div class="col-md-3">
        <input-text
          label="From"
          name="from"
          type="date"
          v-validate="'required'"
          v-model="range.from"
          required
          :error-text="errors.first('from')"
        ></input-text>
      </div>

      <div class="col-md-3">
        <input-text
          label="To"
          name="to"
          type="date"
          v-validate="'required'"
          v-model="range.to"
          required
          :error-text="errors.first('to')"
        ></input-text>
      </div>

      <div class="col-md-1">
        <button
          type="button"
          style="margin-top: 25px"
          @click.prevent="applyRange"
          class="btn btn-success pull-right"
        >
          Apply
        </button>
      </div>
    </div>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/payment-log/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td
          @click="copyOrderNo(row.token)"
          title="Token/Transaction Id used for verification"
          v-if="row.paymentMode === 'khalti'"
        >
          <router-link
            :to="{
              name: 'khaltilog.index',
              params: { idx: row.token },
            }"
            title="Click to go"
          >
            <span class="badge cursor">{{ row.token }}</span>
          </router-link>
        </td>
        <td
          @click="copyOrderNo(row.token)"
          title="Token/Transaction Id used for verification"
          v-if="row.paymentMode === 'esewa'"
        >
          <router-link
            :to="{
              name: 'esewalog.index',
              params: { idx: row.token },
            }"
            title="Click to go"
          >
            <span class="badge cursor">{{ row.token }}</span>
          </router-link>
        </td>
        <td
          @click="copyOrderNo(row.token)"
          title="Token/Transaction Id used for verification"
          v-if="row.paymentMode === 'cod' || row.paymentMode === 'gogoWallet'"
        >
          <span class="badge cursor">{{ row.token }}</span>
        </td>
        <td title="Payment Through">
          {{ row.paymentMode }}
        </td>
        <td title="Total Amount">Rs. {{ row.billAmt | commaNumberFormat }}</td>

        <td v-if="row.type === 'order'">
          <span v-if="row.taskId"> Order No</span> <span v-else>-</span><br />
          <router-link
            :to="{
              name: 'order.index',
              params: { state: row.task.ref_number },
            }"
            title="Click to go"
          >
            <span
              class="badge cursor"
              @click="copyOrderNo(row.task.ref_number)"
              title="OnDemand Service"
              >{{ row.task.ref_number }}</span
            ><br />
            <span class="badge" title="Order Status">{{
              row.task.status
            }}</span>
          </router-link>
        </td>
        <td v-if="row.type === 'trip'">
          <span v-if="row.taskId"> Trip No</span><span v-else>-</span><br />
          <router-link
            :to="{
              name: 'trip.index',
              params: { word: row.task.ref_number },
            }"
            title="Click to go"
          >
            <span
              class="badge cursor"
              @click="copyOrderNo(row.task.ref_number)"
              title="Ride Hailing Service"
              >{{ row.task.ref_number }}</span
            ><br />
            <span class="badge" title="Trip Status">{{ row.task.status }}</span>
          </router-link>
        </td>

        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <br />
          {{ row.user.phone }}
        </td>

        <td v-if="row.type === 'order'">
          {{ row.vendor.business_name }}
          <br />
          {{ row.vendor.phone }} {{ row.vendor.email }}
        </td>
        <td v-else>
          {{ row.vendor }}
        </td>

        <td title="Transaction Staus">
          {{ row.verified ? "Payment Success" : "Payment Failure" }}
        </td>
        <td title="Service Log">
          {{ row.action }} <br />
          <span class="badge">{{ row.service }}</span>
        </td>
        <td>
          {{ row.createdAt }}
        </td>
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
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import PaymentLog from "@utils/models/PaymentLog";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "PaymentLogIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: [
        "TransId/Token",
        "Mode",
        "Bill Amt",
        "Task",
        "User",
        "Vendor",
        "Verified",
        "Action",
        "Added On",
      ],
      model: new PaymentLog(),
      range: {
        from: null,
        to: null,
      },
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },

    copyOrderNo(no) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = no;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);

      alertMessage("Content copied to clipboard.");
    },
    applyRange() {
      if (this.range.from === null || this.range.to === null) {
        alertMessage("Please select date range.", "danger");
        return;
      }
      axios
        .get(
          this.model.indexUrl +
            "/range?from=" +
            this.range.from +
            "&to=" +
            this.range.to
        )
        .then((response) => {
          this.rows.data = response.data.data;
          this.rows.links = response.data.links;
          this.rows.meta = response.data.meta;
        });
    },
  },
  created() {
    this.getModels();
  },
  mounted() {
    this.range.from = moment().subtract(7, "days").format("YYYY-MM-DD");
    this.range.to = moment().format("YYYY-MM-DD");
  },
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
