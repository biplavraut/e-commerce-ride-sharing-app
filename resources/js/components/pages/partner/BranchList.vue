<template>
  <app-card title="All <b>Branches</b>" body-padding="0">
    <template slot="actions">
        <app-btn-link route-name="partner.branches" :data="{parentId:this.$route.params.parentId}">Add New</app-btn-link>
        <!-- <router-link :to="{name:'partner.branches', params:{parentId:this.$route.params.parentId}}" class="btn btn-round btn-xs title-right-action btn-success"> Add New </router-link> -->
    </template>

    <app-table-sortable
      @orderHasChanged="changeOrder"
      :columns="columns"
      :rows="rows"
      sortable
      :paginate="false"
    >
      <template slot-scope="{ row }">
        <!-- <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td> -->
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
      columns: ["Vendor", "Name", "Expire In", "Hidden", "Added On"],
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
    getExistingBranches(){
        axios
        .get("/admin/partner/branches/" + this.$route.params.parentId)
        .then((data) => (this.rows = data.data));
    }
  },

  mounted() {
      this.parent = this.$route.params.hasOwnProperty("parentId");

        this.getExistingBranches();
    //this.getModels();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
</style>