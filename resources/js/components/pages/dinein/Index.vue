<template>
  <app-card :title="' <b>DineIn Form Requests</b>'" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <td>
          {{ row.user.firstName }} {{ row.user.lastName }} <br />
          <small>{{ row.user.phone }}</small>
        </td>
        <td>
          {{ row.vendor.businessName }} <br />
          <small>{{ row.vendor.phone }}</small>
        </td>
        <td>{{ row.date }} {{ row.time }}</td>
        <td>
          {{ row.peopleAttend }}
        </td>
        <td>
          {{ row.totalBill }}
        </td>
        <td>
          <a :href="row.bill" target="_blank" class="badge">See Bill Image</a>
        </td>
        <td>
          <span class="badge">{{ row.status }}</span>
        </td>
        <td>
          {{ row.createdAt }}
        </td>
        <td>
          <button
            type="button"
            title="Mark as Completed"
            class="btn btn-success btn-ajax"
            @click.prevent="updateStatus(row, 'completed')"
            :status="row.status"
            v-if="row.status === 'processing'"
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>
          <button
            type="button"
            class="btn btn-success btn-ajax"
            disabled
            v-else
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Dinein from "@utils/models/Dinein";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "DineinIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: [
        "User",
        "Vendor",
        "DateTime",
        "People",
        "Total Bill",
        "Bill",
        "Status",
        "Submitted On",
      ],
      model: new Dinein(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    updateStatus(row, status) {
      if (confirm("Are you sure? You cannot undo this action.")) {
        axios
          .get(
            this.model.indexUrl +
              "/update-status?formId=" +
              row.id +
              "&status=" +
              status
          )
          .then((response) => {
            if (response.data.status) {
              this.rows.data = this.rows.data.filter(
                (item) => item.id !== row.id
              );
              alertMessage("Dine in request has been set to " + status + ".");
            } else {
              alertMessage("Somethign went wrong.", "danger");
            }
          });
      }
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
.title-right-action1 {
  right: 15px;
  top: 6px;
  float: right;
  margin-left: 10px;
}
</style>
