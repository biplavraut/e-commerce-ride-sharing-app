<template>
  <app-card title="Road Block <b>Notifications</b>" body-padding="0">
    <template slot="actions"> </template>

    <app-table-sortable :columns="columns" :rows="rows">
      <template slot-scope="{ row }">
        <td>{{ row.title }}</td>
        <td>{{ type(row.type) }}</td>
        <td class="feedback" @click="showDesc(row)">
          {{ replaceTags(row.description.substring(0, 15)) + "......" }}
        </td>
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td width="100">
          <span class="label label-primary">{{
            row.showImageOnTop == 1 ? "Yes" : "No"
          }}</span>
        </td>
        <td width="100">
          <span v-if="row.status" class="label label-success">active</span>
          <span v-if="!row.status" class="label label-warning">deactive</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            :actions="{
              edit: {
                name: 'road-block.edit',
                params: { id: row.id },
              },
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import RoadBlockNotification from "@utils/models/RoadBlockNotification";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "RoadBlockMessageIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Title",
        "Type",
        "Description",
        "Image",
        "Image on top",
        "Status",
        "Created At",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new RoadBlockNotification(),
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
    type(name) {
      return name === "user"
        ? "For User App"
        : name === "rider"
        ? "For Rider App"
        : name === "vendor"
        ? "For Vendor App"
        : "Other";
    },
  },

  mounted() {
    this.getModels();
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