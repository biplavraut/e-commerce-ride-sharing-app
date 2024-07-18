<template>
  <div class="row">
    <div class="col-md-7">
      <app-card title="Vendor with Discount List">
        <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
          <template slot-scope="{ row }">
            <td width="50">
              <img :src="row.image50" style="width: auto; height: 50px" />
            </td>
            <td>{{ row.name }}</td>
            <td>{{ row.discount }} %</td>
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
      <app-card title="Add New Vendor Discount">
        <input-text
          label="Find Vendor to Add"
          name="search-text"
          v-model="search"
          @input="searchVendor"
        ></input-text>
        <ul class="list-group list-group-flush">
          <li
            class="list-group-item"
            v-for="item in findVendors"
            :key="item.id"
            v-show="search.trim().length > 0"
          >
            <div class="row">
              <div class="col-md-9">
                {{ item.business_name }} <small>{{ item.phone }}</small>
              </div>
              <div class="col-md-3">
                <span
                  ><button
                    @click="addNewVendor(item.id)"
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
import { store, save, destroy } from "@utils/mixins/Crud";
import moment from "moment";
import { mapGetters } from "vuex";
import VendorDiscount from "@utils/models/VendorDiscount";

export default {
  name: "VendorDiscountIndex",

  mixins: [store, save, destroy],

  data() {
    return {
      edit: false,
      columns: ["Image", "Vendor", "Discount", "Created At"],
      rows: { data: [], links: {}, meta: {} },
      form: new Form({
        vendor_id: "",
        discount: "",
        status: 1,
      }),
      search: "",
      findVendors: [],
      model: new VendorDiscount(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    addNewVendor(vendorId) {
      if (this.rows.data.some((e) => e.vendorId === vendorId)) {
        alert("Vendor Exist in this listed.");
        return;
      }
      this.form.vendor_id = vendorId;
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
            .post(this.model.indexUrl) // POST form data
            .then((response) => {
              if (response.status) {
                alertMessage(response.message);
                this.loadData();
              } else {
                alertMessage(response.message);
                alertMessage("Action cannot be processed.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
    searchVendor() {
      let q = this.search;
      axios
        .get(this.model.indexUrl + "/find-vendor?q=" + q)
        .then((data) => {
          this.findVendors = data.data;
        })
        .catch(() => {});
    },

    loadData: function () {
      axios.get(this.model.indexUrl).then((data) => (this.rows = data.data));
    },
    clearError() {
      this.form.errors.errors = {};
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
        this.findVendors = [];
      }
    },
  },
};
</script>
