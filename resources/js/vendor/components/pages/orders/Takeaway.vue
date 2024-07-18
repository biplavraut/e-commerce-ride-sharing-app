<template>
  <app-card :title="'Takeaway <b>Order List</b>'" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <td title="Order Ref Number">{{ row.orderNo }}</td>
        <td @click="copyOrderNo(row.orderNo)" class="cursor">
          {{ row.orderNo }} <br />
          <span class="badge" v-bind:style="{ background: row.dynamicColor }">{{
            row.agoTime
          }}</span>
        </td>
        <td title="Ordered By">{{ row.order_by }} <br /></td>

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
        <td>
          <button
            type="button"
            title="Mark as Delivered"
            class="btn btn-success btn-ajax"
            @click.prevent="markAsDelivered(row)"
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
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Order from "@utils/models/Order";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "TakeawayOrderIndex",

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
    async takeawayOrderList() {
      let orders = await this.model.getTakeawayList();
      this.rows = orders;
    },
    markAsDelivered(row) {
      if (confirm("Are you sure? You cannot undo this action.")) {
        swal({
          text: "Enter 4 Digit OTP given by customer.",
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
          } else if (otp != row.otp) {
            return swal("Oh noes!", "OTP doesnot match. Try again!", "error");
          } else {
            swal("Success!", "OTP matched.", "success");
            swal.stopLoading();
            swal.close();
            axios
              .get(this.model.indexUrl + "/deliver?orderId=" + row.id)
              .then((response) => {
                if (response.data == "success") {
                  this.rows.data = this.rows.data.filter(
                    (item) => item.id !== row.id
                  );
                  alertMessage("Order has been marked as delivered.");
                } else {
                  alertMessage("Item cannot be marked as delivered.", "danger");
                }
              });
          }
        });
      }
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
    copyOrderNo(no) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = no;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);

      alertMessage("Order no copied to clipboard.");
    },
  },
  created() {
    // this.getModels();
    this.takeawayOrderList();
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
