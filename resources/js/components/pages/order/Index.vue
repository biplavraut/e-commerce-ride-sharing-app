<template>
  <app-card title="All <b>Orders</b>" body-padding="0">
    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li
        :class="activeTab != 'PENDING' ? 'active' : ''"
        @click.prevent="reset"
      >
        <a href="#all" data-toggle="tab" aria-expanded="true"
          >All <span class="badge">{{ allCount }}</span></a
        >
      </li>
      <li
        v-for="(item, index) in status"
        :key="index"
        @click.prevent="fetchCategoryData(item.key)"
        :class="activeTab === item.key ? 'active' : ''"
      >
        <a :href="'#' + item.key" data-toggle="tab" aria-expanded="true"
          >{{ item.key }}
          <sup>
            <span class="badge">{{ item.value }}</span>
          </sup>
        </a>
      </li>
    </ul>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/admin/order/get-data?name='"
      :word="keyword"
    >
      <template slot-scope="{ row }">
        <td>
          <router-link
              :to="{
                name: 'order.detail',
                params: { id: row.id ,},
              }"
              title="View Detail"
            >
              {{ row.orderNo }}
            </router-link>
          <span class="badge" v-bind:style="{ background: row.dynamicColor }">{{
            row.agoTime
          }}</span>
          <span
            class="badge"
            v-bind:style="{ background: row.dynamicColor }"
            v-if="row.takeaway"
            >takeaway</span
          >
          <span class="badge" v-bind:style="{ background: row.dynamicColor }">{{
            row.refNumber
          }}</span>
          
        </td>

        <td>
          {{ row.user.firstName }} {{ row.user.lastName }}
          <br />
          {{ row.user.phone }}
        </td>
        <td :title="row.vendor.email">
          {{ row.vendor.businessName }}
          <br />
          {{ row.vendor.phone }}
        </td>

        <!-- <td>
          {{ row.specialInstruction ? row.specialInstruction : "-" }}
        </td> -->

        <!-- <td @click="copyOrderItem(row.items)">
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
        </td> -->

        <!-- <td>
          <ul>
            <li v-for="item in row.items" :key="item.id">
              <span class="badge" v-if="item.size">{{ item.size }}</span>
              <span v-if="item.size">/</span>
              <span class="badge" v-if="item.color">{{ item.color }}</span>
            </li>
          </ul>
        </td> -->

        <!-- <td>{{ row.location_area }}</td> -->
        <td>{{ row.location_area }} <br> 
          Rs {{ row.shipping_charge }}</td>
        <td>
          <a
            v-bind:href="'/getLocation?lat=' + row.lat + '&lang=' + row.long"
            target="_blank"
            :title="'View on map: '+ row.location"
            >{{ row.location.substring(0, 40) }}</a
          >
        </td>
        <td>Rs. {{ row.total | commaNumberFormat }}</td>
        <td>{{ row.payment_mode }}</td>
        <!-- <td>
          {{ row.nearestLandmark ? row.nearestLandmark : "-" }}
        </td> -->

        <td
          class="cursor"
          data-toggle="modal"
          data-target="#info"
          @click="
            changeInfo(row.id, row.status, row.delivery_date, row.user.id)
          "
          :title="row.delivery ? 'Delivery Status: ' + row.delivery.status : ''"
        >
          {{ row.status }}
        </td>
        <!-- <td>
          Date: {{row.preferred_deli_date}}
          Time: {{row.preferred_deli_time}}
        </td> -->
        <td
          class="cursor"
          data-toggle="modal"
          data-target="#info"
          @click="
            changeInfo(row.id, row.status, row.delivery_date, row.user.id)
          "
        >
          {{ row.delivery_date ? row.delivery_date : "-" }}
        </td>
        <!-- <td>Rs. {{ row.refundableAmount }}</td> -->

        <td>
          {{ formatDate(row.ordered_on) }}
        </td>

        <td v-if="!row.delivery">
          <div
            class="col-md-4"
            v-if="
              row.status
                ? row.status === 'PENDING' || row.status === 'CONFIRMED'
                : ''
            "
          >
            <button
              type="button"
              title="Assign Rider"
              class="btn btn-success btn-ajax"
              data-toggle="modal"
              data-target="#assignRider"
              @click="getRiders(row.id)"
              v-if="!row.takeaway"
            >
              <i class="material-icons">event_seat</i>
              <div class="ripple-container"></div>
            </button>
          </div>
          
          <div
            class="col-md-4"
            v-if="authUser.type === 'admin' || authUser.type === 'superadmin' || authUser.type === 'unit-head'"
          >
            <button
              v-if="row.status !== 'CANCELLED' && row.status !== 'DELIVERED'"
              type="button"
              title="Cancel Order"
              class="btn btn-danger btn-ajax"
              @click.prevent="deleteOrder(row.id)"
            >
              <i class="material-icons">close</i>
              <div class="ripple-container"></div>
            </button>
          </div>
          <div class="col-md-4">
            <router-link
              :to="{
                name: 'order.detail',
                params: { id: row.id ,},
              }"
              title="View Detail"
              class="btn btn-primary btn-ajax"
            >
            <i class="material-icons">preview</i>
          </router-link>
          </div>
        </td>

        <td v-if="row.delivery" title="Delivery Status">
          <div class="col-md-4">
            <router-link
              :to="{
                name: 'order.detail',
                params: { id: row.id ,},
              }"
              title="View Detail"
              class="btn btn-primary btn-ajax"
            >
            <i class="material-icons">preview</i>
          </router-link>
          </div>
          <br>
          <span
            v-if="row.delivery.status !== 'delivered'"
            class="badge badge-warning"
            >{{ row.delivery.status }}</span
          >
          <span v-else class="badge badge-success">Order Delivered</span>
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
                    type="datetime-local"
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

    <div class="modal fade" id="assignRider" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Assign rider for this delivery</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="assignRiderForDelivery">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" v-if="!!riderdata.riders">
                    <div class="list-group">
                      <div
                        v-for="(eachRider, index) in riderdata.riders.data"
                        :key="index"
                        :value="eachRider.id"
                      >
                        <input
                          type="radio"
                          name="selectrider"
                          :value="eachRider.id"
                          :id="eachRider.id"
                          v-model="riderdata.assignedRider"
                        />
                        <label class="list-group-item" :for="eachRider.id"
                          >{{ eachRider.name }}:{{ eachRider.phone }} |
                          Junction:
                          <b>{{ eachRider.junction }}</b>
                          <div class="row">
                            <small class="col-sm-3">Today's Summary:</small>
                            <small class="col-sm-3"
                              >Assigned:{{ eachRider.totalAssigned }}</small
                            >
                            <small class="col-sm-3"
                              >Delivered:{{ eachRider.totalDelivered }}</small
                            >
                            <small class="col-sm-3"
                              >Remaining:{{
                                eachRider.totalAssigned -
                                eachRider.totalDelivered
                              }}</small
                            >
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-9 col-md-3">
                  <button
                    v-if="riderdata.assignedRider"
                    type="submit"
                    :disabled="errors.any()"
                    class="btn btn-success"
                    style="margin-top: 20%"
                  >
                    Assign
                  </button>
                </div>
              </div>
            </form>
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
      radioGroup: 1,
      columns: [
        "Order_Ref",
        "User",
        "Vendor",
        // "Special Instruction",
        // "Products",
        // "Size/Color",
        // "Shipping_Area",
        "Shipping",
        "Shipping Address",
        "Price",
        "Payment Mode",
        // "Refund Amount",
        // "Nearest Landmark",
        "Status",
        // "Preferred Delivery",
        "Delivery Date",
        "Ordered On",
      ],
      orders: [],
      status: [],
      allCount: 0,
      model: new Order(),
      keyword: "",
      activeTab: "all",
      statuses: [
        // {
        //   id: "PROCESSING",
        //   name: "PROCESSING",
        // },
        // {
        //   id: "PACKED",
        //   name: "PACKED",
        // },
        // {
        //   id: "ON THE WAY",
        //   name: "ON THE WAY",
        // },
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
      riderdata: {
        riders: null,
        assignedRider: "",
        orderId: "",
      },
    };
  },

  methods: {
    async getstatus() {
      let status = await axios.get("/admin/order/get-status");
      this.status = status.data.data.map((item) => {
        this.allCount += item.value;
        return {
          key: item.key,
          value: item.value,
        };
      });
    },
    async fetchCategoryData(key) {
      let data = await this.model.getAll(key);
      this.rows.data = data.data;
      this.rows.meta = data.meta;
      this.rows.links = data.links;
    },
    getRiders(orderid) {
      this.riderdata.orderId = orderid;
      axios.get("/admin/get-rider/").then((response) => {
        if (response.data != null) {
          this.riderdata.riders = response.data;
        } else {
          alertMessage("Unable to retrive driver", "danger");
        }
      });
    },
    assignRiderForDelivery() {
      axios
        .post("/admin/assign-delivery", {
          orderId: this.riderdata.orderId,
          rider_id: this.riderdata.assignedRider,
        })
        .then((response) => {
          if (response.data.status === true) {
            alertMessage("Delivery successfully assigned to rider.");
            $("#assignRider").modal("hide");
            this.reset();
          } else {
            alertMessage("Something went wrong while processing", "danger");
          }
        })
        .catch(function (error) {
          alertMessage("Something went wrong while processing", "danger");
        });
      this.reset();
    },
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    changeInfo(orderNo, status, deliveryDate, user) {
      this.info = { orderNo, user };
    },
    reset() {
      axios.get("/admin/order").then((response) => {
        this.rows.data = response.data.data;
        this.rows.meta = response.data.meta;
        this.rows.links = response.data.links;
      });
      this.activeTab = "all";
    },
    updateStatus() {
      // if (this.authUser.type == "admin" || this.authUser.type == "superadmin" || this.authUser.type == "unit-head") {
      //   alertMessage('Unauthorized Access! Contact Your Admin.', "warning")
      //   return;
      // }
      if (!this.info.deliveryDate && !this.info.status) {
        alertMessage(
          "Please set delivery date or status to proceed.",
          "danger"
        );
        return;
      }
      axios
        .put("/admin/order/" + this.info.orderNo, this.info)
        .then((response) => {
          if (response.data === "success") {
            alertMessage("Order Info has been updated.");
            $("#info").modal("hide");
            this.reset();
          } else {
            alertMessage(response.data, "danger");
          }
        });
      this.reset();
    },
    deleteOrder(no) {
      if (
        confirm(
          "Are you sure? You will not be able to change the state in the future"
        )
      ) {
        swal({
          text: "Cancellation Reason",
          content: "input",
          button: {
            text: "Submit!",
            closeModal: false,
          },
        }).then((reason) => {
          if (!reason || reason.length < 5) {
            return swal("Oh noes!", "Write complete reason!", "error");
          } else {
            axios
              .delete(this.model.indexUrl + "/" + no + "?reason=" + reason)
              .then((response) => {
                if (response.data == "success") {
                  //this.reset();
                  this.rows.data = this.rows.data.filter(
                    (item) => item.id !== no
                  );
                  alertMessage("Ordered Item set to cancelled.");
                } else {
                  if(response.data == false){
                    alertMessage("Item cannot be deleted.", "danger");
                  }else{
                    alertMessage(response.data, "danger");
                  }
                }
              });
            swal.stopLoading();
            swal.close();
          }
        });
      }
    },
    dispatchOrder(orderId) {
      axios
        .post("/admin/order/dispatch", { order: orderId })
        .then((response) => {
          if (response.data.status) {
            alertMessage(response.data.message);
            this.reset();
          } else {
            alertMessage("Order cannot be processed.", "danger");
          }
        });
      this.reset();
    },
    copyOrderNo(no) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = no;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);

      alertMessage("Order no copied to clipboard.");
    },
    copyOrderItem(items) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      for(var i = 0; i< items.length; i++ ){
        dummy.value = items.name;
      }
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);

      alertMessage("Order Items copied to clipboard.");
    },
  },
  created() {
    if (this.$route.params.state) {
      this.keyword = this.$route.params.state;
    }
    if (this.$route.params.active) {
      this.fetchCategoryData("PENDING");
      this.activeTab = "PENDING";
    } else {
      this.getModels();
    }
  },
  mounted() {
    this.getstatus();
    // console.log('Hello'+this.$route.params.state);
    // if (!this.$route.params.state) {
    //   // alert('Hello Order');
    //   this.getModels();
    // }
  },
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
.list-group-item {
  user-select: none;
}

.list-group input[type="checkbox"] {
  display: none;
}

.list-group input[type="checkbox"] + .list-group-item {
  cursor: pointer;
}

.list-group input[type="checkbox"] + .list-group-item:before {
  content: "\2713";
  color: transparent;
  font-weight: bold;
  margin-right: 1em;
}

.list-group input[type="checkbox"]:checked + .list-group-item {
  background-color: #022584;
  color: #fff;
}

.list-group input[type="checkbox"]:checked + .list-group-item:before {
  color: inherit;
}

.list-group input[type="radio"] {
  display: none;
}

.list-group input[type="radio"] + .list-group-item {
  cursor: pointer;
}

.list-group input[type="radio"] + .list-group-item:before {
  content: "\2022";
  color: transparent;
  font-weight: bold;
  margin-right: 1em;
}

.list-group input[type="radio"]:checked + .list-group-item {
  background-color: #0275d8;
  color: #fff;
}

.list-group input[type="radio"]:checked + .list-group-item:before {
  color: inherit;
}
</style>
