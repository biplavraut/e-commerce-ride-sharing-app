<template>
  <app-card :title="title" body-padding="0">
    <template slot="actions" v-if="expandStatus">
      <a
        href="#"
        class="btn btn-round btn-xs title-right-action1 btn-success"
        @click="reset"
        title="Go Back"
      >
        <i class="material-icons">reply</i></a
      >
    </template>
    <button
      class="btn btn-round btn-xs title-right-action"
      @click="getAcceptedOrders"
      title="Refresh"
      v-if="!expandStatus"
    >
      <i class="material-icons">assignment</i>
      Accepted Orders
    </button>
    <button
      class="btn btn-round btn-xs title-right-action"
      @click="reset"
      title="Refresh"
      v-if="!expandStatus"
    >
      <i class="material-icons">refresh</i>
      Reset
    </button>
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/vendor/order/get-data?name='"
      v-if="!expandStatus"
    >
      <template slot-scope="{ row }">
        <td title="Order Number">
          {{ row.orderNo }} <br />
          <small v-if="row.delivery">
            <span class="badge" v-if="row.delivery.driver" title="Accepted by">
              Rider: {{ row.delivery.driver.firstName }}
              {{ row.delivery.driver.lastName }} ({{
                row.delivery.driver.phone
              }})</span
            ></small
          >
        </td>
        <td @click="copyOrderNo(row.orderNo)" class="cursor">
          {{ row.orderNo }} <br />
          <span
            class="badge"
            v-bind:style="{ background: row.dynamicColor }"
            v-if="row.takeaway"
            >takeaway</span
          >
        </td>
        <td title="Ordered By">
          {{ row.order_by }} <br />
          <!-- <small
            class="badge"
            v-if="row.delivery"
            title="Verify this otp with rider"
          >
            otp: {{ row.delivery.otp }}</small
          > -->
        </td>

        <td>
          {{ row.specialInstruction ? row.specialInstruction : "-" }}
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
        <td>Rs. {{ row.total }}</td>
        <td>
          {{ formatDate(row.ordered_on) }}
        </td>
        <td v-if="!row.accepted">
          <button
            type="button"
            title="Accept"
            class="btn btn-warning btn-ajax"
            @click.prevent="acceptOrder(row.id)"
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>

          <button
            type="button"
            title="Cancel Order"
            class="btn btn-danger btn-ajax"
            @click.prevent="cancelOrder(row.id)"
          >
            <i class="material-icons">cancel</i>
            <div class="ripple-container"></div>
          </button>

          <button
            type="button"
            title="Expand Order"
            class="btn btn-success btn-ajax"
            @click.prevent="expandOrder(row)"
          >
            <i class="material-icons">expand</i>
            <div class="ripple-container"></div>
          </button>
        </td>
        <td width="100" v-if="row.accepted">
          <button
            type="button"
            title="Verify OTP with Delivery Rider"
            class="btn btn-success btn-ajax"
            @click.prevent="verifyOtp(row)"
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>
        </td>
      </template>
    </app-table-sortable>

    <div
      class="table-responsive"
      style="overflow: scroll !important"
      v-if="expandStatus"
    >
      <table class="table table-hover">
        <thead>
          <tr>
            <th>S.N.</th>
            <th v-for="(col, index) in itemCol" :key="index">
              {{ col }}
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item, index) in expandableOrder.items" :key="index">
            <td>{{ ++index }}</td>
            <td width="100">
              <img
                :src="item.product.image50"
                style="width: 50px; height: 50px; border-radius: 50%"
              />
            </td>
            <td style="font-weight: bold">{{ item.product.title }}</td>
            <td>Rs. {{ item.price }}</td>
            <td style="font-weight: bold">
              <a
                href="#"
                @click.prevent="updateItemQuantity(item, '+')"
                title="Increase Ordere Item Quantity"
              >
                <span class="material-icons"> add_circle </span></a
              >
              {{ item.quantity }}
              <a
                href="#"
                @click.prevent="updateItemQuantity(item, '-')"
                title="Decrease Ordere Item Quantity"
                v-if="item.quantity > 1"
              >
                <span class="material-icons"> remove_circle </span></a
              >
            </td>
            <td>{{ item.color }} / {{ item.size }}</td>
            <td width="100">
              <button
                type="button"
                title="Delete Order Item"
                class="btn btn-danger btn-ajax"
                v-if="expandableOrder.items.length > 1"
                @click.prevent="deleteItem(item.id)"
              >
                <i class="material-icons">delete_forever</i>
                <div class="ripple-container"></div>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
      title: "All <b>Orders</b>",
      columns: [
        "#",
        "Order No.",
        "Ordered By",
        "Special Instruction",
        "Products",
        "Size/Color",
        "Price",
        "Ordered On",
      ],
      orders: [],
      model: new Order(),
      expandableOrder: {},
      expandStatus: false,
      itemCol: [
        "Image",
        "Title",
        "Price",
        "Quantiy",
        "Color / Size",
        "Actions",
      ],
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    acceptOrder(no) {
      axios
        .get(this.model.indexUrl + "/accept-order?orderId=" + no)
        .then((response) => {
          if (response.data == "success") {
            this.rows.data = this.rows.data.filter((item) => item.id !== no);
            alertMessage("Order has been verified.");
          } else {
            alertMessage("Item cannot be verified.", "danger");
          }
        });
    },
    async getAcceptedOrders() {
      let orders = await this.model.getAcceptedOrder();
      this.rows = orders;
    },
    async reset() {
      let orders = await this.model.getAll();
      this.rows = orders;
      this.expandStatus = false;
      this.expandableOrder = {};
      this.title = "All <b>Orders</b>";
    },
    cancelOrder(no) {
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
            .get(
              this.model.indexUrl +
                "/cancel-order?orderId=" +
                no +
                "&reason=" +
                reason
            )
            .then((response) => {
              if (response.data == "success") {
                this.rows.data = this.rows.data.filter(
                  (item) => item.id !== no
                );
                alertMessage("Order has been cancelled.");
              } else {
                alertMessage("Order cannot be cancelled right now.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
    expandOrder(order) {
      this.expandStatus = true;
      this.expandableOrder = order;
      this.title = "Ref #<b>" + order.refNumber + "</b>";
    },
    deleteItem(itemId) {
      var response = confirm("Are you sure want to process this action?");
      if (response) {
        axios
          .get(
            this.model.indexUrl +
              "/delete-item?itemId=" +
              itemId +
              "&orderId=" +
              this.expandableOrder.id
          )
          .then((response) => {
            if (response.data == "success") {
              this.expandableOrder.items = this.expandableOrder.items.filter(
                (item) => item.id !== itemId
              );
              alertMessage("Order Item has been deleted.");
            } else {
              alertMessage("Item cannot be deleted.", "danger");
            }
          });
      }
    },
    updateItemQuantity(item, action) {
      if (action === "+") {
        item.quantity++;
      } else {
        item.quantity--;
      }
      axios
        .get(
          this.model.indexUrl +
            "/update-item?itemId=" +
            item.id +
            "&orderId=" +
            this.expandableOrder.id +
            "&quantity=" +
            item.quantity
        )
        .then((response) => {
          if (response.data == "success") {
            alertMessage("Item Quantity updated.");
          } else {
            alertMessage("Item Quantity cannot updated.", "danger");
          }
        });
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
    verifyOtp(row) {
      if (confirm("Are you sure? You cannot undo this action.")) {
        swal({
          text: "Enter 4 Digit OTP given by delivery rider.",
          content: "input",
          button: {
            text: "Submit!",
            closeModal: false,
          },
        }).then((otp) => {
          if (!otp || otp.length < 4) {
            return swal(
              "Oh noes!",
              "Please enter 4 digit otp to continue!",
              "error"
            );
          } else if (otp != row.delivery.otp) {
            return swal("Oh noes!", "OTP doesnot match. Try again!", "error");
          } else {
            const index = this.rows.data.indexOf(row);
            if (index > -1) {
              this.rows.data.splice(index, 1);
            }
            swal(
              "Success!",
              "OTP matched successfully. Now you can give delivery items to delivery rider.",
              "success"
            );
            swal.stopLoading();
            swal.close();
          }
        });
      }
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
.title-right-action1 {
  right: 15px;
  top: 6px;
  float: right;
  margin-left: 10px;
}
</style>
