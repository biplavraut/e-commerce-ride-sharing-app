<template>
  <app-card title="All <b>Utility Services</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="additional-service.create"
        >Add New</app-btn-link
      >
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
        <td>{{ row.cashback }}</td>
        <td>
          <span class="badge">{{ row.enabled }}</span>
        </td>
        <td>
          <span class="badge">{{ row.enabledPromo }}</span>
        </td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'additional-service.edit',
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
import AddService from "@utils/models/AddService";
import { index, destroy } from "@utils/mixins/Crud";
import { mapMutations } from "vuex";

export default {
  name: "AdditionalServiceIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Name", "Subtitle", "Cashback", "Enabled", "Enabled Promo"],
      rows: { data: [], links: {}, meta: {} },
      model: new AddService(),
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
