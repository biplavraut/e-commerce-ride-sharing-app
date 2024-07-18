<template>
  <app-card title="All <b>Campaigns</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="campaign.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/campaign/get-data?name='"
      searchHolder="Search (By Name)"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>{{ formatValidDate(row.heldOn) }}</td>
        <td>
          <li v-for="(item, index) in row.winners" :key="index" class="badge">
            User:{{ item }} | Prize: {{ row.prizes[index] }} || Type:
            {{ row.types[index] }}
          </li>
        </td>
        <td>{{ row.userType === "user" ? "AppUser" : row.userType }}</td>
        <td>{{ row.createdAt }}</td>
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
import Campaign from "@utils/models/Campaign";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "CampaignIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Name", "Held On", "Winners", "userType", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Campaign(),
    };
  },

  methods: {
    formatValidDate(date) {
      return moment(date).format("LL");
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
</style>