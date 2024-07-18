<template>
  <app-card title="All <b>Order Returns</b>" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <td>
          <span class="badge cursor" title="Return Ticket">{{
            row.ticket
          }}</span>
          <br/>
          <span>Requested on:  {{ row.createdAt }}</span>
        </td>            
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <br />
          {{ row.user.phone }}
        </td>
        <td>
          {{ row.vendor.first_name }} {{ row.vendor.last_name }}
          <br />
          {{ row.vendor.phone }}
        </td>
        <td @click="showReason(row)" style="cursor: pointer">
          {{ row.reason.substring(0, 100) }}
        </td>
        <td @click="statusChange(row.user.id, row.id, row.status)"><span class="badge cursor" title="Return Status">{{ row.status }}</span></td>
        <td width="20%">
          <ul>
            <li>{{ 'Name: '+ row.orderItem.name }}</li>
            <li>{{ 'Price: '+ row.orderItem.price }}</li>
            <li>{{ 'gogo Price: '+ row.orderItem.gogo_price }}</li>
            <li> {{ 'Selling Price: '+ sellingPrice(row.orderItem) }}</li>
          </ul>     
        </td>
        <td style="white-space: pre-line">{{ row.remarks }}</td>
        <td
          v-if="(authUser.type === 'admin' || authUser.type === 'superadmin') && row.status != 'resolved'"
        >
          <button
              class="btn btn-primary btn-xs"
              @click="resolveWithoutPoint(row.user.id, row.id ,sellingPrice(row.orderItem))"
            >
              Resolve without Point
              <div class="ripple-container"></div>
          </button>
          <button
              v-if="row.status == 'accepted' || row.status == 'accepted-by-vendor'"
              class="btn btn-success btn-xs"
              @click="addWalletPoint(row.user.id, row.id ,sellingPrice(row.orderItem))"
            >
              Add Wallet Point
              <div class="ripple-container"></div>
          </button>
        </td>
        <td v-else>Resolved</td>
      </template>
    </app-table-sortable>
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
            <form>
              <div class="row">
                <div class="col" id="returnAmount">
                  <div class="form-group">
                    <label for="amount" class="col-form-label">Points*</label>
                    <input
                      type="number"
                      v-model="form.amount"
                      id="amount"
                      class="form-control"
                      min="0"
                      required
                      disabled
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="remarks" class="col-form-label">Remarks*</label>
                    <input
                      type="text"
                      v-model="form.remarks"
                      class="form-control"
                      id="remarks"
                      required
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancle
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="submitResolve()"
            >
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of input Model -->
    <!-- Start of status Modal  -->
    <div
      class="modal fade"
      id="addNewStatusModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewStatusModelLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              id="statusModelClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="addNewStatusModelLabel"></h5>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="status">Status*</label>
                    <select class="form-control" id="status" v-model="form.status" required>
                      <option
                          data-tokens=""
                          :value="item.value"
                          v-for="(item, index) in returnStatus"
                          :key="index"
                        >
                          {{ item.name }}
                        </option>
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancle
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="updateReturnStatus()"
            >
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of status Model -->
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import OrderReturn from "@utils/models/OrderReturn";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "OrderReturnIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      radioGroup: 1,
      columns: ["Ticket", "User" ,"Vendor", "Reason", "Status", 'Order Item','Remarks'],
      model: new OrderReturn(),
      response: {
        returnId: 0,
      },
      form: new Form({
        user_id: "",
        order_return_id: "",
        amount: "",
        remarks: "",
        wallet: false,
        status:'',
      }),
      addfund: false,
      returnStatus: [
        {
          value: "requested",
          name: "Requested",
        },
        {
          value: "processing",
          name: "Processing",
        },
        {
          value: "accepted",
          name: "Accept by gogo20",
        },
        {
          value: "declined",
          name: "Decline by gogo20",
        },               
        {
          value: "proceed-to-vendor",
          name: "Proceed to vendor",
        },
        {
          value: "accepted-by-vendor",
          name: "Accepted by vendor",
        },         
        {
          value: "declined-by-vendor",
          name: "Declined by vendor",
        },
      ],
    };
  },

  methods: {
    sellingPrice(item){
      var discount = 0
      if(item.discount_type == 'amount'){
        var discount = item.discount
      }else{
        var disc_percent = item.discount
        var discount = Math.round((item.price * disc_percent)/100);
      }
      return parseFloat(item.price - discount - item.elite_price) + parseFloat(item.tax_amt) + parseFloat(item.service_charge_amt)
    },
    addWalletPoint(user, orId, sp) {
      this.addfund = true;
      this.form.reset();
      this.form.order_return_id = orId;
      this.form.user_id = user;
      this.form.amount = sp;
      this.form.wallet= true
      var modal = $("#addNewModel").modal();
      $("#returnAmount").show();
      var text = "Resolve Return? by Adding " + sp + " Points to user.";
      modal.find(".modal-title").text(text);
      modal.show();
    },
    resolveWithoutPoint(user, orId, sp){
      this.addfund = false;
      this.form.reset();
      this.form.order_return_id = orId;
      this.form.user_id = user;
      this.form.amount = sp;
      this.form.wallet= false
      var modal = $("#addNewModel").modal();
      $("#returnAmount").hide();
      var text = "Resolve Return? without Adding Points to user.";
      modal.find(".modal-title").text(text);
      modal.show();
    },
    submitResolve() {
      swal({
        title: "Are you sure?",
        text: "Once Added, you will not be able to revert the process!",
        icon: "warning",
        buttons: ["Cancle!", "Resolve!"],
        dangerMode: true,
      }).then((result) => {
        if (result) {
          if (!this.form.amount || !this.form.user_id || !this.form.remarks || !this.form.order_return_id) {
            swal("Invalid Input");
            throw null;
          } else {
            $("#modelClose").click();
            this.form.post("/admin/resolve-return").then((response) => {
              if (response.status) {
                alertMessage(response.message);
                this.form.reset();
                this.reset();
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
            swal.stopLoading();
            swal.close();
          }
        }
      });
    },
    showReason(row) {
      swal(
        row.user.first_name + " " + row.user.last_name + "'s Return",
        row.reason
      );
    },
    statusChange(user, orId, status){
      this.addfund = false;
      this.form.reset();
      this.form.order_return_id = orId;
      this.form.user_id = user;
      this.form.status = status
      this.form.wallet= false
      var modal = $("#addNewStatusModel").modal();
      var text = "Change Status";
      modal.find(".modal-title").text(text);
      modal.show();
    },
    updateReturnStatus() {
      swal({
        title: "Are you sure?",
        text: "Status will be reflected in User and Vendor App",
        icon: "warning",
        buttons: ["Cancle!", "Update!"],
        dangerMode: true,
      }).then((result) => {
        if (result) {
          if (!this.form.status || !this.form.user_id || !this.form.order_return_id) {
            swal("Invalid Input");
            throw null;
          } else {
            $("#statusModelClose").click();
            this.form.post("/admin/change-return-status").then((response) => {
              if (response.status) {
                alertMessage(response.message);
                this.form.reset();
                this.reset();
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
            swal.stopLoading();
            swal.close();
          }
        }
      });
    },
    reset() {
    axios.get("/admin/order-return").then((response) => {
      this.rows.data = response.data.data;
      this.rows.meta = response.data.meta;
      this.rows.links = response.data.links;
    });
  },
  },
  
  created() {},
  mounted() {
    this.getModels();
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
</style>
