
<template>
  <app-card title="All <b>Reviews</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/vendor/product/get-reviews/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td width="100">
          <img
            :src="image.image150"
            style="width: 30px; height: 30px; border-radius: 30%; margin: 1%"
            v-for="(image, index) in row.images"
            @click="viewImage(image)"
            title="Click to see"
            :key="index"
          />
        </td>
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <small>({{ row.user.phone }})</small>
        </td>
        <td>
          {{ row.product.title }}
          <small>( code: {{ row.product.code }})</small>
        </td>
        <td>{{ row.review }}</td>
        <td>{{ row.rating }}</td>
        <td>{{ row.likes }}</td>
        <!-- <td>{{ row.answer }}</td> -->
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              delete: row.id,
            }"
          ></app-actions>
          <br />
          <button
            type="button"
            title="Verify Now"
            class="btn btn-warning btn-ajax"
            @click="verifyNow(row)"
          >
            <i class="material-icons">done</i>
          </button>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import ProductReview from "@utils/models/ProductReview";
import { index, destroy } from "@utils/mixins/Crud";
import { mapMutations } from "vuex";

export default {
  name: "VendorProductReviewIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Images",
        "User",
        "Product",
        "Review",
        "Rating",
        "Likes",
        "Reviewed On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new ProductReview(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    verifyNow(row) {
      this.model.verify(row.id);
      this.rows.data.splice(row, 1);
      alertMessage("Review and Rating Verified");
    },
    viewImage(image) {
      window.location.href = image.image;
    },
  },

  mounted() {
    this.getModels();
  },
  created() {},
  watch: {},
};
</script>

<style scoped>
.card-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /* background-color : #337AB7; */
  color: #666666;
}
</style>