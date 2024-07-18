<template>
  <app-card title="All <b>Socials</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="social.create">Add New</app-btn-link>
    </template>

    <app-table-sortable @orderHasChanged="changeOrder"
                        :columns="columns"
                        :paginate="false"
                        sortable
                        :rows="rows">

      <template slot-scope="{ row }">
        <td width="100">
          <img :src="row.icon50"
               style="width:50px;height:50px;">
        </td>
        <td>{{ row.name }}</td>
        <td><a :href="row.url" target="_blank">{{ row.url }}</a></td>
        <td width="100">
          <app-actions @deleteItem="deleteModel"
                       :actions="{edit:{name: 'social.edit', params: {id: row.id}}, delete:row.id}"></app-actions>
        </td>
      </template>

    </app-table-sortable>
  </app-card>
</template>

<script>
  import Social from "@utils/models/Social";
  import {index, destroy} from "@utils/mixins/Crud";

  export default {
    name: "SocialIndex",

    mixins: [index, destroy],

    data() {
      return {
        columns: ["Icon", "Name", "Url"],
        rows: {data: [], links: {}, meta: {}},
        model: new Social()
      };
    },

    methods: {
      async changeOrder(payload) {
        await this.model.changeOrder(payload);
        alertMessage('Order changed successfully.');
      },
    },

    mounted() {
      this.getModels();
    }
  };
</script>
