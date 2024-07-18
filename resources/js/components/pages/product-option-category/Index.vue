<template>
  <app-card title="All <b>Product Option Categories</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link
        route-name="product-option-category.create"
        :data="serviceId"
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
        <td>{{ row.products }}</td>
        <td>{{ row.layout }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: {
                name: 'product-option-category.edit',
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
import ProductOptionCategory from "@utils/models/ProductOptionCategory";
import Service from "@utils/models/Category";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "ProductOptionCategoryIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Title", "Slug", "Service", "Total Products", "Layout"],
      rows: { data: [], links: {}, meta: {} },
      model: new ProductOptionCategory(),
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
          count: item.productOptionCount,
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
