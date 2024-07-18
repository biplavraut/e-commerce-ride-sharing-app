<template>
  <app-card title="All <b>Operational Users</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link
        route-name="super-user.create"
        v-if="authUser.email === 'drb@gogo20.com'"
        >Add New</app-btn-link
      >
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/super-user/get-data?name='"
      reverse
    >
      <template slot-scope="{ row }">
        <td width="100">
          <a :href="row.image" target="_blank">
            <img :src="row.image" style="width: 50px; height: 50px" />
          </a>
        </td>
        <td>{{ row.name }}</td>
        <td>
          {{ row.email ? row.email : "-" }}
        </td>
        <td>{{ row.type.toUpperCase() }}</td>

        <td>
          <span class="badge">{{ row.verified ? "Active" : "Blocked" }}</span>
        </td>
        <td>{{ row.recentActive }}</td>

        <td>{{ formatDate(row.createdAt) }}</td>
        <td v-if="authUser.type === 'admin' || authUser.type === 'superadmin'">
          <div class="col-md-4">
            <app-actions
              :actions="{
                edit: { name: 'super-user.edit', params: { id: row.id } },
              }"
            ></app-actions>
          </div>
          <div class="col-md-4">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
          <div class="col-md-4">
            <button
              @click="unverify(row)"
              type="button"
              :title="row.verified ? 'Mark As Unverified' : 'Mark As Verified'"
              :class="
                row.verified
                  ? 'btn btn-warning btn-ajax'
                  : 'btn btn-success btn-ajax'
              "
            >
              <i class="material-icons">{{
                row.verified ? "block" : "vpn_key"
              }}</i>
            </button>
          </div>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import SuperUser from "@utils/models/SuperUser";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "SuperUserIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },

      columns: [
        "Image",
        "FullName",
        "E-mail",
        "Role",
        "Status",
        "Last Active",
        "Joined On",
      ],
      model: new SuperUser(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    reset() {
      this.rows = { data: [], links: {}, meta: {} };
      this.getModels();
    },

    unverify(row) {
      if (confirm("Are you sure? You want to execute this task.")) {
        axios
          .get(this.model.indexUrl + "/unverify?adminId=" + row.id)
          .then((response) => {
            alertMessage(response.data);
            if (row.verified) {
              row.verified = false;
            } else {
              row.verified = true;
            }
          });
      }
      // location.reload();
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
