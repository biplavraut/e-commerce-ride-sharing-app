<template>
  <app-card title="All <b>Orders</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/vendor/order/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>
          {{ row.user.firstName }} {{ row.user.lastName }}
          <br />
          {{ row.user.phone }}
        </td>
        <td>
          <ul>
            <li
              v-for="(item, index1) in row.items"
              :key="index1"
              :title="'Rs. ' + item.price"
            >
              {{ item.name }}
              <span class="badge">{{ item.product.code }} </span>
              ({{ item.quantity }})
            </li>
          </ul>
        </td>
        <td>
          <ul>
            <li v-for="item in row.items" :key="item.id">
              <span class="badge" v-if="item.size">{{ item.size }}</span>
              <span v-if="item.size">/</span>
              <span class="badge" v-if="item.color">{{ item.color }}</span>
            </li>
          </ul>
        </td>
        <td>{{ row.location }}</td>
        <td
          class="cursor"
          data-toggle="modal"
          data-target="#info"
          @click="changeInfo(row.id, row.status, row.deliveryDate, row.user.id)"
        >
          {{ row.status }}
        </td>
        <td
          class="cursor"
          data-toggle="modal"
          data-target="#info"
          @click="changeInfo(row.id, row.status, row.deliveryDate, row.user.id)"
        >
          {{ row.delivery_date ? formatDate(row.delivery_date) : "-" }}
        </td>
        <td>{{ row.payment_mode }}</td>
        <td>Rs. {{ row.total }}</td>
        <td>
          {{ formatDate(row.orderedOn) }}
        </td>
        <td width="100">
          <button
            type="button"
            title="Delete"
            class="btn btn-danger btn-ajax"
            @click.prevent="deleteOrder(row.id)"
          >
            <i class="material-icons">close</i>
            <div class="ripple-container"></div>
          </button>
        </td>
      </template>
    </app-table-sortable>

    <!-- Modal -->
    <div class="modal fade" id="info" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Change Order Info</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateStatus">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select v-model="info.status" class="form-control">
                      <option
                        class="lead"
                        v-for="(status, index) in statuses"
                        :key="index"
                        :value="status.id"
                      >
                        {{ status.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <input-text
                    label="Delivery Date"
                    name="delivery_date"
                    :error-text="errors.first('delivery_date')"
                    v-model="info.deliveryDate"
                    type="date"
                  ></input-text>
                </div>
                <div class="col-md-3">
                  <button
                    type="submit"
                    :disabled="errors.any()"
                    class="btn btn-success"
                    style="margin-top: 20%"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-card>
</template>

<script>
import Order from "@utils/models/Order";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "OrdersIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },

      columns: [
        "User",
        "Products",
        "Size/Color",
        "Shipping Address",
        "Status",
        "Delivery Date",
        "Payment Mode",
        "Price",
        "Ordered On",
      ],
      orders: [],
      model: new Order(),
      statuses: [
        {
          id: "PENDING",
          name: "PENDING",
        },
        {
          id: "PACKED",
          name: "PACKED",
        },
        {
          id: "ON THE WAY",
          name: "ON THE WAY",
        },
        {
          id: "DELIVERED",
          name: "DELIVERED",
        },
      ],
      info: {
        orderNo: "",
        status: "",
        deliveryDate: "",
        user: "",
      },
      keyword: "",
      filterOrders: [],
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    changeInfo(orderNo, status, deliveryDate, user) {
      deliveryDate =
        deliveryDate === "-"
          ? new Date().toISOString().slice(0, 10)
          : deliveryDate;
      this.info = { orderNo, status, deliveryDate, user };
    },
    reset() {
      axios.get("/vendor/order").then((response) => {
        this.rows.data = response.data.data;
      });
    },
    updateStatus() {
      axios.post("/vendor/order", this.info).then((response) => {
        if (response.data.status) {
          alertMessage("Order Info has been updated.");
          $("#info").modal("hide");

          this.reset();
        } else {
          alertMessage("Order Info cannot be updated.", "danger");
        }
      });
      this.reset();
    },

    deleteOrder(no) {
      axios.delete(this.model.indexUrl + "/" + no).then((response) => {
        if (response.data == "success") {
          this.filterOrders = this.filterOrders.filter(
            (item) => item.id !== no
          );
          alertMessage("Ordered Item deleted.");
        } else {
          alertMessage("Item cannot be deleted.", "danger");
        }
      });
    },
  },
  created() {
    this.getModels();
  },
  mounted() {},
  computed: {
    ...mapGetters(["authUser"]),
  },
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
