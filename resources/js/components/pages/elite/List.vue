<template>
  <app-card title="gogoElite <b>Request List</b>" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <td
          @click="copy(row.userId)"
          style="cursor: pointer"
          title="click to copy"
        >
          {{ row.userId }}
        </td>
        <td>
          {{ row.firstName }} {{ row.lastName }} <br />
          <span class="badge"> {{ row.phone }}</span> /
          <span class="badge">{{ row.email ? row.email : "-" }}</span>
        </td>

        <td>{{ row.address ? row.address : "-" }}</td>

        <td>{{ row.gogoWallet }}</td>
        <td>Nrs. {{ row.totalSpentOnOrder }}</td>
        <td>{{ formatDate(row.createdAt) }}</td>
        <td>{{ row.recentLogin }}</td>

        <td
         
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-success btn-ajax"
              v-if="!row.elite"
              @click.prevent="eliteState(row, 'yes')"
              title="Mark as gogoElite Member"
            >
              <i class="material-icons">verified_user</i>
            </button>
            <button
              type="button"
              class="btn btn-danger btn-ajax"
              v-if="row.elite"
              @click.prevent="eliteState(row, 'no')"
              title="Remove from gogoElite Member"
            >
              <i class="material-icons">remove</i>
            </button><br>
          </div>

          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-danger btn-ajax"
              @click.prevent="requestDelete(row)"
            >
              <i class="material-icons">delete</i>
            </button>
          </div>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import User from "@utils/models/User";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "EliteRequestListIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: [
        "GUID",
        "Full_Name",
        "Address",
        "gogoPoint",
        "Total Spent on Orders",
        "Joined On",
        "Last Login",
      ],
      model: new User(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    copy(no) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = no;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);
      alertMessage("Content copied to clipboard.");
    },
    getList() {
      axios
        .get(this.model.indexUrl + "/elite-request-list")
        .then((response) => {
          this.rows = response.data;
        });
    },
    eliteState(row, state) {
      if (confirm("Are you sure? You want to execute this action.")) {
        axios
          .get(
            this.model.indexUrl +
              "/elite-state?user=" +
              row.id +
              "&state=" +
              state
          )
          .then((response) => {
            if (response.data === "success") {
              alertMessage("User has been marked as gogoElite Member.");
              const index = this.rows.data.indexOf(row);
              if (index > -1) {
                this.rows.data.splice(index, 1);
              }
            } else {
              alertMessage("Something went wrong.", "danger");
            }
          });
      }
    },
    requestDelete(row) {
      if (confirm("Are you sure? You want to delete this request.")) {
        axios
          .get(this.model.indexUrl + "/elite-request-delete?user=" + row.id)
          .then((response) => {
            if (response.data === "success") {
              alertMessage("Request Deleted.");
              const index = this.rows.data.indexOf(row);
              if (index > -1) {
                this.rows.data.splice(index, 1);
              }
            } else {
              alertMessage("Something went wrong.", "danger");
            }
          });
      }
    },
  },
  created() {
    this.getList();
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
