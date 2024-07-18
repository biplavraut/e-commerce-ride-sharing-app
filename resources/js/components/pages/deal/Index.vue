<template>
  <app-card title="All <b>Deals</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="deal.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      reverse
    >
      <template slot-scope="{ row }">
        <td width="180"><img :src="row.image" style="width: auto; height: 90px" /></td>
        <td>{{ row.title }}<br><span  class="badge">{{ row.categoryName }}</span></td>
        <td>{{ row.from }}</td>
        <td>{{ row.to }}</td>
        <td><span class="badge">{{ row.status ? "Enabled" : "Disabled" }}</span> <br>
        <small>Starts: {{ row.starts }}</small> <br>
         <small>Ends: {{ row.ends }}</small> 
        </td>
        <td>{{ formatDate(row.createdAt) }}</td>
        <td>
          <div class="col-md-4">
            <router-link :to="{name:'deal.products', params:{dealId:row.id}}" class="btn btn-primary action-add btn-ajax btn-link" type="button" title="Add Products"><i class="material-icons">add</i></router-link>
          </div>
          <div class="col-md-4" >
            <app-actions
              :actions="{
                edit: { name: 'deal.edit', params: { id: row.id } },
              }"
            ></app-actions>
          </div>
          <div class="col-md-4" v-if="authUser.type === 'admin' || authUser.type === 'superadmin'">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Deal from "@utils/models/Deal";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";


export default {
  name: "DealIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: ["Image", "title", "From", "To", "Status", "Created At"],
      model: new Deal(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },

  },
  mounted() {
    this.getModels();
  },
  watch: {},
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
