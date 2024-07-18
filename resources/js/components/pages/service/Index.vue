<template>
  <app-card title="All <b>Services</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="service.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      @orderHasChanged="changeOrder"
      :paginate="false"
      sortable
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.name }}</td>
        <td>{{ row.subtitle }}</td>
        <td>
          <span class="badge">{{ row.openingClosingTime }}</span>
        </td>
        <td>
          <span class="badge">{{ row.ondemand }}</span>
        </td>
        <td>
          <span class="badge">{{ row.showProductCategory }}</span>
        </td>
        <td>
          <span class="badge">{{ row.enabled }}</span>
        </td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'service.edit',
                params: { id: row.id },
              },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Category from "@utils/models/Category";
import { index, destroy } from "@utils/mixins/Crud";
import { mapMutations } from "vuex";

export default {
  name: "ServiceIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Image",
        "Name",
        "Subtitle",
        "OpeningClosingTime",
        "OnDemand",
        "Show Product Categories",
        "Enabled",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Category(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
    },
  },

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped></style>
