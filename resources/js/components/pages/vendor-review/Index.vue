<template>
  <app-card title="<b>Vendor Rating &amp; Review</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/vendor-review/get-data?name='"
      :searchHolder="'Search (By Review, Rating)'"
    >
      <template slot-scope="{ row }">
        <td>
          {{ row.vendor.business_name }} <br />
          <span title="Full Name"
            >({{ row.vendor.first_name }} {{ row.vendor.last_name }})</span
          >
          <br />
          <span class="badge">{{ row.vendor.phone }}</span> /
          <span class="badge">{{ row.vendor.email }}</span>
        </td>
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <br />
          <span class="badge">{{ row.user.phone }}</span> /
          <span class="badge">{{ row.user.email }}</span>
        </td>
        <td @click="showReview(row.review)" style="cursor:pointer;">
          {{ row.review.substring(0, 10) }} ...
        </td>
        <td>
          {{ row.rating }}
        </td>
        <td>
          {{ row.createdAt }}
        </td>

        <td>
          <div class="col-md-6" v-if="!row.verified">
            <button
              @click.prevent="verify(row)"
              title="Verify this Review"
              class="btn btn-warning btn-ajax btn-link"
            >
              <i class="material-icons">done </i>
            </button>
          </div>

          <div class="col-md-6">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import VendorReview from "@utils/models/VendorReview";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "VendorReviewIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: ["Vendor", "User", "Review", "Rating", "Rated On"],
      model: new VendorReview(),
    };
  },

  methods: {
    async verify(row) {
      if (confirm("Are you sure? You can't undo this action.")) {
        let verify = await this.model.verify(row.id);
        alertMessage("Operation Success");
        const index = this.rows.data.indexOf(row);
        if (index > -1) {
          this.rows.data.splice(index, 1);
        }
      }
    },
    showReview(review) {
      swal("Review", review);
    },
  },
  created() {},
  mounted() {
    this.getModels();
  },
  computed: {},
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
