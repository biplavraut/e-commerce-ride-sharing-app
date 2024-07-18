<template>
  <app-card title="All <b>Academy Contents</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="academy-content.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/academy-content/get-data?name='"
      :searchHolder="'Search (By Title, Video Url, Description)...'"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.title }}</td>
        <td>{{ row.url ? row.url : "-" }}</td>
        <td
          @click="copy(row.videoUrl)"
          style="cursor: pointer"
          title="Click to copy"
        >
          {{ row.videoUrl ? row.videoUrl : "-" }}
        </td>
        <td>{{ row.fors }}</td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'academy-content.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import AcademyContent from "@utils/models/AcademyContent";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "AcademyContentIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "URL", "Video Url", "For", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new AcademyContent(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    copy(name) {
      copyContent(name, "Link copied to clipboard.");
    },
  },

  mounted() {
    this.getModels();
  },
};
</script>

<style scoped></style>
