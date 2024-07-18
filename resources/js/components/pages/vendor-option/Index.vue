<template>
  <app-card title="All <b>Vendor Options Sort</b>" body-padding="0">
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
        <td>{{ row.vendor.businessName }}</td>
        <td width="100">-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import VendorOption from "@utils/models/VendorOption";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import Service from "@utils/models/Category";

export default {
  name: "VendorOptionIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Category", "Vendor"],
      rows: { data: [], links: {}, meta: {} },
      model: new VendorOption(),
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
          count: item.vendors,
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
