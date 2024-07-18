<template>
  <app-card title="Gogo <b>Pool</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :rows="rows"
    >
      <template slot-scope="{ row }">
        <td>{{ row.date }}</td>
        <td>{{ row.current_location }}</td>
        <td>{{ row.desire_destination }}</td>
        <td>{{ row.distance_in_km }} KM</td>
        <td>{{ row.vechical_type }}</td>
        <td>{{ row.status == 0 ?"inactive":"active" }}</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Pool from "@utils/models/Pool";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "PoolIndex",
  mixins: [index, destroy],
  data() {
    return {
      columns: ["#", "Origin","Destination","Distance","Vechicle Type","Status"],
      rows: { data: [], links: {}, meta: {} },
      model: new Pool(),
    };
  },

  methods: {},

  mounted() {
    this.getModels();
  },
};
</script>
<style scoped>
</style>