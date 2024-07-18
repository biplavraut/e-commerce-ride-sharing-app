<template>
<div>
    <div class="row">
        <div class="col-md-6">
          <app-card title="Users">
              <input-text
              label="Find User"
              name="search-text"
              v-model="searchUserTitle"
              @input="searchUser"
              ></input-text>
              <ul class="list-group list-group-flush">
              <li
                  class="list-group-item"
                  v-for="item in usersList"
                  :key="item.id"
                  v-show="searchUserTitle.trim().length > 0"
              >
                  <div class="row">
                      <div class="col-md-8">
                          {{ item.firstName +' '+ item.lastName }}, ({{ item.phone }})
                      </div>
                      <div class="col-md-4">
                          <span><button
                              @click="loadCart(item.id)"
                              class="btn btn-sm btn-primary">
                              Select
                          </button></span>
                      </div>
                  </div>
              </li>
              </ul>
          </app-card>
        </div>
        <div class="col-md-6">
            <app-card title="Add New Products">
                <input-text
                label="Find Product to Add"
                name="search-text"
                v-model="searchProductTitle"
                @input="searchProduct"
                :disabled ="form.user_id == ''"
                ></input-text>
                <ul class="list-group list-group-flush">
                <li
                    class="list-group-item"
                    v-for="item in findProducts"
                    :key="item.id"
                    v-show="searchProductTitle.trim().length > 0"
                >
                    <div class="row">
                    <div class="col-md-9">
                        {{ item.title }} <small>{{ item.vendor.business_name }}</small>
                        <small>(Stock: {{item.openingStock }})</small>
                    </div>
                    <div class="col-md-3">
                        <span
                        ><button v-if="item.openingStock > 0"
                            @click="addProductDetail(item)"
                            class="btn btn-sm btn-primary"
                        >
                            Add
                        </button>
                        <button v-else class="btn btn-sm btn-primary"> Out of Stock</button>
                        </span
                        >
                    </div>
                    </div>
                </li>
                </ul>
            </app-card>
            </div>
    </div>
  <div class="row">
    <div class="col-md-12">
      <app-card title="Cart Products">
        <app-table-sortable
          :columns="columns"
          :rows="rows"
          :paginate="false"
        >
          <template slot-scope="{ row }">
            
            <td>
                <span width="50">
                    <img :src="row.product.image50" style="width: auto; height: 50px" />
                </span>
                {{ row.product.title }}</td>
            <td>Rs. {{ row.product.price }}</td>
            <td>{{ row.quantity }} </td>
            <td>{{ row.size }} </td>
            <td>{{ row.color }} </td>
            <td
              v-if="authUser.type === 'admin' || authUser.type === 'superadmin' || authUser.type === 'unit-head'"
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
        <button v-show="changesDetected" class="btn btn-primary" @click="notifyUser(form.user_id)">Notify User</button>
      </app-card>
    </div>    
  </div>
  <!-- Start of Input Modal  -->
    <div
      class="modal fade"
      id="addNewModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewModelModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form @submit.prevent="addNewProduct">
          <div class="modal-header">
            <button
              type="button"
              id="modelClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="addNewModelModalLabel"></h5>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="quantity" class="col-form-label">Quantity*</label>
                    <input
                      type="number"
                      v-model="form.quantity"
                      id="quantity"
                      class="form-control"
                      min="1"
                      :max="maxQty"
                      required
                    />
                  </div>
                </div>
                <div class="col" v-show="size.length > 0">
                  <div class="form-group asdh-select" name="role">
                    <label>Size</label>
                    <select class="form-control" v-model="form.size" :required="size.length > 0">
                      <option value disabled>Select Size</option>
                      <option
                        data-tokens=""
                        v-for="(option, index) in size"
                        :key="index"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col" v-show="color.length > 0">
                  <div class="form-group asdh-select" name="role">
                    <label>Color</label>
                    <select class="form-control" v-model="form.color" :required="color.length > 0">
                      <option value disabled>Select Color</option>
                      <option
                        data-tokens=""
                        v-for="(option, index) in color"
                        :key="index"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="btn btn-success"
            >
              Save
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End of input Model -->
</div>
</template>

<script>
import Form from "@utils/Form";
import { store, save } from "@utils/mixins/Crud";
import moment from "moment";
import { mapGetters } from "vuex";
import Cart from "@utils/models/Cart";

export default {
  name: "CartProduct",

  mixins: [store, save],

  data() {
    return {
      changesDetected: false,
      usersList: [],
      columns: [
        "Product",
        "Price",
        "Quantity",
        "Size",
        "Color",
      ],
      rows: { data: [], links: {}, meta: {} },
      form: new Form({
        user_id: "",
        product_id: "",
        quantity: 1,
        size: "",
        color:"",
        special_instruction:""
      }),
      size:[],
      color:[],
      searchUserTitle: "",
      searchProductTitle: "",
      findProducts: [],
      model: new Cart(),
      maxQty:''
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    addProductDetail(item){
      // item.id, item.title, item.vendor.business_name, item.size, item.color, item.maxQty
      this.maxQty = item.openingStock
      this.form.product_id = item.id;
      if (this.rows.data.some((e) => e.product.id === this.form.product_id)) {
        alert("Product Exist in this cart.");
        return;
      }
      this.size = item.size;
      this.color = item.color;
      var modal = $("#addNewModel").modal();
      var text = "Add to Cart: " + item.title + ", " + item.vendor.business_name;
      modal.find(".modal-title").text(text);
      modal.show();
    },
    addNewProduct() {
      if (this.rows.data.some((e) => e.product.id === this.form.product_id)) {
        alert("Product Exist in this cart.");
        return;
      }
      if (
        confirm(
          "Are you sure? You will not be able to recover your data in the future."
        )
      ){      
        this.form
          .post("/admin/cart-products/add") // POST form data
          .then((response) => {
            if (response.status) {
              $("#modelClose").click();
              alertMessage(response.message);
              this.changesDetected = true;
              this.findProducts = [];
            } else {
              alertMessage("Action cannot be processed.", "danger");
            }
          });
        this.loadCart(this.form.user_id);
      }
      else{

      }
    },
    searchUser: debounce(function (e) {
      let q = this.searchUserTitle;
      axios
        .get(
            "/admin/user/get-data?name=" +q
            // "/admin/find-user?q=" + q
        )
        .then((response) => {
          this.usersList = response.data.data;
        })
        .catch(() => {});
    }, 1000),
    searchProduct: debounce(function (e) {
      let q = this.searchProductTitle;
      axios
        .get(
          "/admin/find-product?q=" + q
        )
        .then((data) => {
          this.findProducts = data.data.data;
        })
        .catch(() => {});
    }, 1000),
    async deleteModel(id) {
      if (
        confirm(
          "Are you sure? You will not be able to recover your data in the future."
        )
      ) {
        await axios.delete("/admin/delete-cart-product/" + id);
        alertMessage("Data successfully deleted.");
        this.rows.data = this.rows.data.filter((item) => item.id !== id);
        this.loadCart(this.form.user_id);
      }
    },
    loadCart: function (userId) {
      axios
        .get("/admin/cart-products/" + userId)
        .then((data) => (this.rows = data.data));
        this.form.user_id = userId;
        this.usersList = [];
    },
    clearError() {
      this.form.errors.errors = {};
    },
    async notifyUser(userId){
      if (
        confirm(
          "Are you sure? Changes will reflect in user app."
        )
      ) {
        await axios.get("/admin/cart-notify-user/" + userId);
        alertMessage("Notification successfully sent.");
        this.loadCart(this.form.user_id);
        this.changesDetected = false;
      }
    }
    
    
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
  created() {
    // this.loadCart();
  },
  watch: {
    searchUser: function (val) {
      if (val.trim().length === 0) {
        this.usersList = [];
      }
    },
    searchProduct: function (val) {
      if (val.trim().length === 0) {
        this.findProducts = [];
      }
    },
  },
};
</script>
