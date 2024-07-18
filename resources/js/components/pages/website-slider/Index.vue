<template>
  <app-card title="All <b>Website Sliders</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="website-slider.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/website-slider/get-data?name='"
      :searchHolder="'Search By ( Caption Text )...'"
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
        <td>{{ row.sliderText }}</td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'website-slider.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import WebsiteSlider from "@utils/models/WebsiteSlider";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "WebsiteSliderIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Caption", "Hide", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new WebsiteSlider(),
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
