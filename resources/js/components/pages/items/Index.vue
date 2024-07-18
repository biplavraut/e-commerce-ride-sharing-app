<template>
  <app-card title="Send Items <b>Details</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="items.create">Add New</app-btn-link>
    </template>
    <app-table-sortable :columns="columns" :rows="rows">
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>{{ row.flatPrice }}</td>
        <td>{{ row.addedPerKmPrice }}</td>
        <td>{{ row.addedWeightpricePerKg }}</td>
        <td>{{ row.status == true ? "active" : "inactive" }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'items.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Items from "@utils/models/Items";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "ItemsIndex",
  mixins: [index, destroy],
  data() {
    return {
      columns: [
        "Name",
        "Flat Price",
        "Added Price / KM",
        "Added Weight Price / KM",
        "Status",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Items(),
    };
  },

  methods: {},

  mounted() {
    this.getModels();
  },
};
</script>
<style scoped></style>
