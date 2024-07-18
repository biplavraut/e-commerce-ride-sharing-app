<template>
  <div class="row">
    <div class="col-md-7">
      <app-card title="Listed Products">
        <app-table-sortable
          :columns="columns"
          :rows="rows"
          @orderHasChanged="changeOrder"
          sortable
          :paginate="false"
        >
          <template slot-scope="{ row }">
            <td width="50">
              <img :src="row.image50" style="width: auto; height: 50px" />
            </td>
            <td>{{ row.name }}</td>
            <td>{{ row.price }}</td>
            <td>{{ row.discount }} %</td>
            <td>{{ row.elite_percent }} %</td>
            <td>
              <small>{{ formatDate(row.created_at) }}</small>
            </td>
            <td
              v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
            >
              <div class="col-md-4">
                <app-actions
                  @deleteItem="deleteModel"
                  :actions="{
                    delete: row.id,
                  }"
                ></app-actions>
              </div>
            </td>
            <td v-else>-</td>
          </template>
        </app-table-sortable>
      </app-card>
    </div>
    <div class="col-md-5">
      <app-card title="Add New Products">
        <input-text
          label="Find Product to Add"
          name="search-text"
          v-model="search"
          @input="searchProduct"
        ></input-text>
        <ul class="list-group list-group-flush">
          <li
            class="list-group-item"
            v-for="item in findProducts"
            :key="item.id"
            v-show="search.trim().length > 0"
          >
            <div class="row">
              <div class="col-md-9">
                {{ item.title }} <small>{{ item.vendor.business_name }}</small>
              </div>
              <div class="col-md-3">
                <span
                  ><button
                    @click="addNewProduct(item.id)"
                    class="btn btn-sm btn-primary"
                  >
                    Add
                  </button></span
                >
              </div>
            </div>
          </li>
        </ul>
      </app-card>
    </div>
  </div>
</template>

<script>
import Form from "@utils/Form";
import { store, save } from "@utils/mixins/Crud";
import moment from "moment";
import { mapGetters } from "vuex";
import Deal from "@utils/models/Deal";

export default {
  name: "DealProduct",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      columns: [
        "Image",
        "Product",
        "Price",
        "Deal Dis",
        "Elite Dis",
        "Created At",
      ],
      rows: { data: [], links: {}, meta: {} },
      form: new Form({
        deal_id: this.$route.params.dealId,
        product_id: "",
        discount: "",
        status: 1,
      }),
      search: "",
      findProducts: [],
      model: new Deal(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    addNewProduct(productId) {
      if (this.rows.data.some((e) => e.productId === productId)) {
        alert("Product Exist in this deal.");
        return;
      }
      this.form.product_id = productId;
      swal({
        title: "Discount %",
        content: {
          element: "input",
          attributes: {
            placeholder: "Discount %",
            type: "number",
            step: "2",
            value: 0,
            min: 0,
            max: 100,
          },
        },
        button: {
          text: "Submit!",
          closeModal: false,
        },
      }).then((discount) => {
        if (!discount) {
          throw null;
        } else {
          this.form.discount = discount;
          this.form
            .post("/admin/deal-products/add") // POST form data
            .then((response) => {
              if (response.status) {
                alertMessage(response.message);
                this.loadData();
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
    searchProduct() {
      let q = this.search;
      axios
        .get(
          "/admin/find-product?q=" + q + "&deal=" + this.$route.params.dealId
        )
        .then((response) => {
          this.findProducts = response.data.data;
        })
        .catch(() => {});
    },
    async deleteModel(id) {
      if (
        confirm(
          "Are you sure? You will not be able to recover your data in the future."
        )
      ) {
        await axios.delete("/admin/delete-deal-product/" + id);
        alertMessage("Data successfully deleted.");
        this.rows.data = this.rows.data.filter((item) => item.id !== id);
        this.loadData();
      }
    },
    loadData: function () {
      axios
        .get("/admin/deal-products/" + this.$route.params.dealId)
        .then((data) => (this.rows = data.data));
    },
    clearError() {
      this.form.errors.errors = {};
    },
    async changeOrder(payload) {
      await this.model.changeOrder(payload);
      alertMessage("Order changed successfully.");
    },
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
  created() {
    this.loadData();
  },
  watch: {
    search: function (val) {
      if (val.trim().length === 0) {
        this.findProducts = [];
      }
    },
  },
};
</script>
