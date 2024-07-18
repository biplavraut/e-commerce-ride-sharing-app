<template>
  <app-card title="All <b>Layout Manager</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="layout-manager.create" :data="serviceId"
        >Add New</app-btn-link
      >
    </template>

    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li class="active" @click.prevent="reset">
        <a href="#all" data-toggle="tab" aria-expanded="true"
          >All <span class="badge">{{ allCount }}</span></a
        >
      </li>
      <li
        v-for="(category, index) in services"
        :key="index"
        @click.prevent="fetchCategoryData(category.id)"
      >
        <a :href="'#' + category.slug" data-toggle="tab" aria-expanded="true"
          >{{ category.name }}
          <sup>
            <span class="badge">{{ category.count }}</span>
          </sup>
        </a>
      </li>
    </ul>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      @orderHasChanged="changeOrder"
      sortable
      :paginate="false"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>{{ row.modelType }}</td>
        <td>{{ row.modelIdCount }}</td>
        <td>{{ row.service.name }}</td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'layout-manager.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Service from "@utils/models/Category";
import LayoutManager from "@utils/models/LayoutManager";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "LayoutManagerIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Name", "Model", "Items Count", "Service", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new LayoutManager(),
      service: new Service(),
      serviceId: "",
      services: [],
      allCount: 0,
      otherCategory: false,
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        return {
          id: item.id,
          name: item.name,
          count: item.layoutCount,
        };
      });
    },
    async fetchCategoryData(id) {
      this.serviceId = id;
      this.otherCategory = true;
      let data = await this.model.getData(id);
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = data.data;
    },
    async reset() {
      this.otherCategory = false;
      await this.getModels();
    },
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
    },
  },

  mounted() {
    this.getModels();
    this.getServices();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
  watch: {
    "rows.data": function (val) {
      if (!this.otherCategory) {
        this.allCount = val.length;
      }
    },
  },
};
</script>

<style scoped>
</style>