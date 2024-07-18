<template>
  <app-card title="Reset <b>Password</b>" body-padding="0">
    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li
        :class="current === 'user' ? 'active' : ''"
        @click.prevent="activeTab('user')"
      >
        <a href="#all" data-toggle="tab" aria-expanded="true">App User</a>
      </li>
      <li
        :class="current === 'rider' ? 'active' : ''"
        @click.prevent="activeTab('rider')"
      >
        <a href="#rider" data-toggle="tab" aria-expanded="true">Rider</a>
      </li>
      <li
        :class="current === 'vendor' ? 'active' : ''"
        @click.prevent="activeTab('vendor')"
      >
        <a href="#vendor" data-toggle="tab" aria-expanded="true">Vendor</a>
      </li>
      <li
        :class="current === 'system' ? 'active' : ''"
        @click.prevent="activeTab('system')"
      >
        <a href="#system" data-toggle="tab" aria-expanded="true">System User</a>
      </li>
    </ul>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/reset-password/get-data' + searchUrl"
      :paginate="false"
      :clearKeyword="clear"
      :searchHolder="'Search (By Name, Phone, Email)'"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>
          {{ row.firstName ? row.firstName + " " + row.lastName : row.name }}
        </td>
        <td>{{ row.email ? row.email : "-" }}</td>
        <td>{{ row.phone }}</td>
        <td width="100">
          <button class="btn btn-primary btn-xs" @click="resetPassword(row.id)">
            Reset Password
            <div class="ripple-container"></div>
          </button>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "ResetPasswordIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Name", "Email", "Phone"],
      rows: { data: [], links: {}, meta: {} },
      current: "user",
      searchUrl: "?type=user&name=",
      clear: false,
    };
  },

  methods: {
    activeTab(type) {
      this.clear = false;
      setTimeout(() => {
        this.clear = true;
      }, 500);
      this.current = type;
      if (type == "user") {
        this.searchUrl = "?type=user&name=";
      } else if (type === "rider") {
        this.searchUrl = "?type=rider&name=";
      } else if (type === "vendor") {
        this.searchUrl = "?type=vendor&name=";
      } else if (type === "system") {
        this.searchUrl = "?type=system&name=";
      }
    },
    resetPassword(userId) {
      if (confirm("Are you sure? This cannot be undo.")) {
        axios
          .get(
            "/admin/reset-password/action?userId=" +
              userId +
              "&type=" +
              this.current
          )
          .then((response) => {
            if (response.data.message === "success") {
              this.clear = false;
              setTimeout(() => {
                this.clear = true;
              }, 500);
              alertMessage(
                "Password has been reset successfully & Send Password Detail as a sms to related user."
              );
            } else {
              alertMessage(response.data);
            }
          });
      }
    },
  },

  mounted() {
    // this.getModels();
  },
};
</script>

<style scoped>
</style>