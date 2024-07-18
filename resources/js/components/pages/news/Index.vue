<template>
  <app-card title="All <b>News</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="news.create">Add New</app-btn-link>
    </template>

    <app-table-sortable :columns="columns"
                        :rows="rows">

      <template slot-scope="{ row }">
        <td width="100">
          <img :src="row.image50"
               style="width:50px;height:50px;border-radius:50%;">
        </td>
        <td>{{ row.name }}</td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions @deleteItem="deleteModel"
                       :actions="{edit:{name: 'news.edit', params: {id: row.id}}, delete:row.id}"></app-actions>
        </td>
      </template>

    </app-table-sortable>
  </app-card>
</template>

<script>
  import News from "@utils/models/News";
  import moment from "moment";
  import {index, destroy} from "@utils/mixins/Crud";

  export default {
    name: "NewsIndex",

    mixins: [index, destroy],

    data() {
      return {
        columns: ["Image", "Title", "Added On"],
        rows: {data: [], links: {}, meta: {}},
        model: new News()
      };
    },

    methods: {
      formatDate(date) {
        return moment(date).format("LLLL");
      }
    },

    mounted() {
      this.getModels();
    }
  };
</script>

<style scoped>
</style>