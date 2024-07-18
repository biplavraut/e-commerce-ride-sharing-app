<template>
  <app-card title="All <b>Partners</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="partner.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      @orderHasChanged="changeOrder"
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/partner/get-data?name='"
      sortable
      :paginate="false"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.vendor ? row.vendor.business_name : "-" }}</td>
        <td>{{ row.name }}</td>
        <td>{{ row.expireIn ? formatDate(row.expireIn) : "-" }}</td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin' || authUser.type === 'unit-head'"
        >
          
          <div class="col-md-8">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                edit: { name: 'partner.edit', params: { id: row.id } },
                delete: row.id,
              }"
            ></app-actions>
          </div>
          <div class="col-md-4">
            <router-link :to="{name:'partner.listbranch', params:{parentId:row.id}}" class="btn btn-primary action-add btn-ajax btn-link" type="button" title="Branches"><i class="material-icons">add</i></router-link>
          </div>
          
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Partner from "@utils/models/Partner";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "PartnerIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Vendor", "Name", "Expire In", "Hidden", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Partner(),
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
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
</style>