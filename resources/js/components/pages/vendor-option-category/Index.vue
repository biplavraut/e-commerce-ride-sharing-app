<template>
  <app-card title="All <b>Vendor Option Categories</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="vendor-option-category.create" :data="serviceId"
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
      :paginate="false"
      sortable
    >
      <template slot-scope="{ row }">
        <td>{{ row.title }}</td>
        <td>{{ row.slug }}</td>
        <td>{{ row.service.name }}</td>
        <td>{{ row.vendors }}</td>
        <td>{{ row.layout }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'vendor-option-category.edit',
                params: { id: row.id },
              },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import VendorOptionCategory from "@utils/models/VendorOptionCategory";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import Service from "@utils/models/Category";

export default {
  name: "VendorOptionCategoryIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Title", "Slug", "Service", "Total Vendors", "Layout"],
      rows: { data: [], links: {}, meta: {} },
      model: new VendorOptionCategory(),
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
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
    },
    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        return {
          id: item.id,
          name: item.name,
          count: item.vendorOptionCount,
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
  },

  mounted() {
    this.getModels();
    this.getServices();
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

<style scoped></style>
