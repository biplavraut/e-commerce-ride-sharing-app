<template>
  <app-card title="All <b>Notifications</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="global-notification.create"
        >Add New</app-btn-link
      >
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/global-notification/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <a :href="row.image" target="_blank">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
          </a>
        </td>
        <td>{{ row.title }}</td>
        <td>{{ row.to.toUpperCase() }}</td>
        <td class="feedback" @click="showDesc(row)">
          {{ replaceTags(row.message.substring(0, 15)) + "......" }}
        </td>
        <td>{{ formatDate(row.createdAt) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <button
            type="button"
            title="Send Again"
            class="btn btn-danger btn-ajax"
            @click="sendAgain(row)"
          >
            <i class="material-icons">send</i>
            <div class="ripple-container"></div>
          </button>
        </td>
        <td v-else>-</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'global-notification.edit',
                params: { id: row.id },
              },
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
import GlobalNotification from "@utils/models/GlobalNotification";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "GlobalNotificationIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "For", "Message", "Added On", ""],
      rows: { data: [], links: {}, meta: {} },
      model: new GlobalNotification(),
      isLoading: false,
      regex: /(<([^>]+)>)/gi,
    };
  },
  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    showDesc(row) {
      swal(row.message.replace(this.regex, ""));
    },
    replaceTags(data) {
      return data.replace(this.regex, "");
    },
    async sendAgain(row) {
      let response = await this.model.sendAgain(row.id);
      if (response === "success") {
        alertMessage("Sent Successfully.");
      } else {
        alertMessage("Error occured while sending notification.", "danger");
      }
    },
  },

  mounted() {
    this.getModels();
  },

  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
.card-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /* background-color : #337AB7; */
  color: #666666;
}
.feedback {
  cursor: pointer;
}
</style>