<template>
  <app-card title="All <b>Ads</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="ad.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/ad/get-data?name='"
      :multiDelete="false"
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
        <td>{{ type(row.type) }}</td>
        <td>{{ row.service }}</td>
        <td>{{ row.url ? row.url : "-" }}</td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'ad.edit', params: { id: row.id } },
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
import Ad from "@utils/models/Ad";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "AdIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "Type", "Service", "URL", "Hidden"],
      rows: { data: [], links: {}, meta: {} },
      model: new Ad(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
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

  computed: {
    ...mapGetters(["authUser"]),
  },

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped>
</style>