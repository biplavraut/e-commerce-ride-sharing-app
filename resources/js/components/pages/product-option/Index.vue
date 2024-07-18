<template>
  <app-card title="All <b>Product Options Sort</b>" body-padding="0">
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
          >{{ category.title }}
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
        <td>{{ row.category.title }}</td>
        <td width="100">
          <img
            :src="row.product.image50"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.product.title }}</td>
        <td width="100">-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import ProductOption from "@utils/models/ProductOption";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import Service from "@utils/models/Category";

export default {
  name: "ProductOptionIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Category", "Product Image", "Product"],
      rows: { data: [], links: {}, meta: {} },
      model: new ProductOption(),
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
      let services = await this.model.getList();

      this.services = services.data.map((item) => {
        return {
          id: item.id,
          title: item.title,
          count: item.products,
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
