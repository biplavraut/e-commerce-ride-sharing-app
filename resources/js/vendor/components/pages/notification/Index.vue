<template>
  <app-card title="All <b>Notifications</b>" body-padding="0">
    <app-table-sortable title="All Notifications" :columns="columns" :actions="false" :rows="rows">
      <template slot-scope="{ row }">
        <td>
          <img :src="row.from.image" style="width:50px;height:50px;border-radius:50%;" />
        </td>
        <td>
          <a href="#" @click.prevent="markAsRead(row)">
            {{ row.message }} from
            <b>{{ row.from.name }}</b>
          </a>
        </td>
        <td>{{ row.createdAt }}</td>
        <td width="160">
          <span v-if="model.isRead(row)" class="material-icons">done</span>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Notification from "@utils/models/Notification";

export default {
  name: "VendorNotificationIndex",

  data() {
    return {
      columns: ["Image", "Message", "Date", "Read?"],
      rows: { data: [], links: {}, meta: {} },
      model: new Notification(),
    };
  },

  methods: {
    async getNotifications() {
      try {
        this.rows = await this.model.getPaginatedListUncached();
      } catch (e) {
        console.log(e);
      }
    },

    markAsRead(notification) {
      this.$router.push(this.model.getUrl(notification));

      if (this.model.isRead(notification)) return;

      this.model
        .markAsRead(notification)
        .then((data) => {
          notification.readAt = data;
        })
        .catch((error) => console.log(error));
    },
  },

  mounted() {
    this.getNotifications();
  },
};
</script>