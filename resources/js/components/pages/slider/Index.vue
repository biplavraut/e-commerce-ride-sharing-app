<template>
  <app-card title="All <b>Sliders</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="slider.create">Add New</app-btn-link>
    </template>

    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li class="active" @click.prevent="reset">
        <a href="#all" data-toggle="tab" aria-expanded="true"
          >All <span class="badge">{{ allCount }}</span></a
        >
      </li>
      <li
        v-for="(category, index) in categories"
        :key="index"
        @click.prevent="fetchCategoryData(category.id)"
      >
        <a :href="'#' + category.slug" data-toggle="tab" aria-expanded="true"
          >{{ category.name }}
          <sup>
            <span class="badge">{{ category.sliderCount }}</span>
          </sup>
        </a>
      </li>
    </ul>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/slider/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="row.image"
            style="width: 50px; height: 50px; border-radius: 50%"
          />
        </td>
        <td>{{ row.category.name }}</td>
        <td>{{ row.name }}</td>
        <td>{{ row.url ? row.url : "-" }}</td>
        <td>
          <span class="badge">{{ row.forLayout }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'slider.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Slider from "@utils/models/Slider";
import Category from "@utils/models/Category";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "SliderIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Image",
        "Service",
        "Title",
        "URL",
        "For Layout Manager",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Slider(),
      category: new Category(),
      categories: [],
      allCount: 0,
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    reset() {
      axios.get("/admin/slider").then((response) => {
        this.rows.data = response.data.data;
      });
    },
    async fetchCategoryData(id) {
      let data = await this.model.getData(id);
      this.rows.data = data.data;
    },
    async getCategories() {
      let categories = await this.category.getAll();

      this.categories = categories.data.map((category) => {
        this.allCount += category.sliderCount;
        return {
          id: category.id,
          name: category.name,
          slug: category.slug,
          sliderCount: category.sliderCount,
        };
      });
    },
  },

  mounted() {
    this.getModels();
    this.getCategories();
  },
};
</script>

<style scoped></style>
