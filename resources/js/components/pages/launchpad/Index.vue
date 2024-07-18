<template>
  <app-card title="All <b>Launchpad</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="launchpad.create">Add New</app-btn-link>
    </template>

    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li class="active" @click.prevent="reset">
        <a href="#all" data-toggle="tab" aria-expanded="true">All</a>
      </li>
      <li
        v-for="(category, index) in categories"
        :key="index"
        @click.prevent="fetchCategoryData(category.id)"
      >
        <a :href="'#' + category.id" data-toggle="tab" aria-expanded="true"
          >{{ category.name }}
          <sup
            ><span class="badge" v-if="category.count > 0">{{
              category.count
            }}</span></sup
          >
        </a>
      </li>
    </ul>

    <app-table-sortable
      @orderHasChanged="changeOrder"
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/launchpad/get-data?name='"
      sortable
      :multiDelete="false"
      :paginate="true"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <a :href="row.image" target="_blank">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
          </a>
        </td>
        <td>{{ row.name }}</td>
        <td>{{ row.url ? row.url : "-" }}</td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'launchpad.edit', params: { id: row.id } },
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
import LaunchpadCategory from "@utils/models/LaunchpadCategory";
import Launchpad from "@utils/models/Launchpad";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "LaunchpadIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["Image", "Title", "URL", "Hidden", "Added On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Launchpad(),
      category: new LaunchpadCategory(),
      categories: [],
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    reset() {
      axios.get("/admin/launchpad").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    async fetchCategoryData(id) {
      let data = await this.model.getData(id);
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = data.data;
      this.rows.links = data.links;
      this.rows.meta = data.meta;
    },
    async getCategories() {
      let categories = await this.category.getAll();

      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name,
          slug: category.slug,
          count: category.count,
        };
      });
    },
    deleteMultiple(data) {
      console.log("delete multiple");
    },
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
    },
  },

  mounted() {
    this.getModels();
    this.getCategories();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
</style>