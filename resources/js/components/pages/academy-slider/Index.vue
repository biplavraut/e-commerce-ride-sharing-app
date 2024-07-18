<template>
  <app-card title="All <b>Academy Sliders</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="academy-slider.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/academy-slider/get-data?name='"
      :searchHolder="'Search (By Name)...'"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.name }}</td>
        <td>{{ row.url ? row.url : "-" }}</td>
        <td>{{ row.fors }}</td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'academy-slider.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import AcademySlider from "@utils/models/AcademySlider";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "AcademySliderIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "URL", "For", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new AcademySlider(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
  },

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped></style>
