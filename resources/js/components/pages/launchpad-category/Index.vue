<template>
  <app-card title="All <b>Launchpad Categories</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="launchpad-category.create"
        >Add New</app-btn-link
      >
    </template>

    <app-table-sortable
      @orderHasChanged="changeOrder"
      :columns="columns"
      :paginate="false"
      sortable
      :rows="rows"
      :searchUrl="'/admin/launchpad-category/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'launchpad-category.edit', params: { id: row.id } },
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
import LaunchpadCategory from "@utils/models/LaunchpadCategory";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "LaunchpadCategoryIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Name"],
      rows: { data: [], links: {}, meta: {} },
      model: new LaunchpadCategory(),
    };
  },

  methods: {
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
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
