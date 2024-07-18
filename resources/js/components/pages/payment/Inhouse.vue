<template>
  <app-card
    title="All In-House Pilots <b>Pending Transaction</b>"
    body-padding="0"
  >
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/rider-log/get-data?name='"
      :searchHolder="'Search (By Rider Name, Phone)'"
    >
      <template slot-scope="{ row }">
        <td title="Order Details">
          <!-- <router-link
            :to="{
              name: 'order.index',
              params: { state: row.order.ref_number },
            }"
            title="Click to go"
          >
            <span class="badge cursor" title="OnDemand Service">{{
              row.order.ref_number
            }}</span>
          </router-link> -->
          <router-link
              :to="{
                name: 'order.detail',
                params: { id: row.order.id ,},
              }"
              title="View Detail"
            >
            <span class="badge cursor" title="Order Ref">{{
              row.order.ref_number
            }}</span>
            </router-link>
        </td>
        <td title="Rider">
          <span class="badge cursor"
            >{{ row.driver.first_name }} {{ row.driver.last_name }} <br />
            {{ row.driver.phone }}
          </span>
        </td>
        <td>Rs. {{ (row.order.total - row.order.shipping_fee) | commaNumberFormat  }}</td>
        <td>Rs. {{ row.order.shipping_fee | commaNumberFormat }}</td>
        <td>Rs. {{ row.total | commaNumberFormat }}</td>

        <td>
          <span class="badge cursor" title="Receiver" v-if="row.receiver"
            >{{ row.receiver.name }} / {{ row.receiver.email }}
          </span>
          <span v-else>-</span>
        </td>

        <td>{{ row.createdAt }}</td>
        <td v-if="row.receiver">{{ row.updatedAt }}</td>
        <td v-else>-</td>
        <td v-if="authUser.type === 'admin' || authUser.type === 'superadmin'">
          <!-- <div class="col-md-6">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div> -->
          <div class="col-md-6">
            <button
              @click.prevent="markAsReceived(row)"
              v-if="!row.receiver"
              title="Mark as Received"
              class="btn btn-warning btn-ajax btn-link"
            >
              <i class="material-icons">done </i>
            </button>
          </div>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import InHousePilot from "@utils/models/InHousePilot";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "InHousePilotIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },

      columns: [
        "Order",
        "Driver",
        "Parcel Amount",
        "Shipping Charge",
        "Total Receivable Amount",
        "Received By",
        "Added On",
        "Received On",
      ],
      model: new InHousePilot(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
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
            .post("/admin/rider-log/receive", {
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
