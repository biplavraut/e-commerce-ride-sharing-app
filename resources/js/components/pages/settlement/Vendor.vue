<template>
  <div>
    <div style="margin-bottom:10px; display:flex;">
      <ul class="nav nav-pills nav-pills-warning">
          <li :class="filter === '7-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('7-days')">7 Days </a>
          </li>
          <li :class="filter === '15-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('15-days')">15 Days </a>
          </li>
          <li :class="filter === '30-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('30-days')">30 Days </a>
          </li>    
      </ul>
    </div>
    <app-card title="All <b>Vendors to Settle</b>" body-padding="0">
      <template slot="actions">
        <app-btn-link
          background="primary"
          icon="done"
          route-name="vendor.settled"
          >SETTLED LIST
      </app-btn-link>
        <app-btn-link background="success"
          icon="view_list" route-name="settlement.advancesettlement">Advance Settlement List</app-btn-link>
      </template>
      <div class="table-responsive" style="overflow: scroll !important">
        <table class="table" style="margin-bottom: 0">
          <thead>
            <tr>
              <th v-for="(column, index) in columns" :key="index">
                {{ column }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in data"
              :key="index"
            >
              <td>{{ ++index }}</td>
              <td>
                {{ item.businessName }}
                <span title="Full Name">({{ item.fullName }})</span>
                <br />
                <span class="badge">{{ item.phone }}</span> /
                <span class="badge">{{ item.email }}</span>
              </td>
              <td>
                <br />
                {{ item.settlementTime }} Days
              </td>
              <td>
                {{ item.totalOrders | commaNumberFormat }}
              </td>
              <td title="OrderTotal">
                Rs.
                {{ item.orderTotal | commaNumberFormat }}
              </td>
              <td title="GoGoTotal">
                Rs.
                {{ item.gogoTotal | commaNumberFormat }}
              </td>
              <td>
                Rs.
                {{ item.vendoAdvanceBalance | commaNumberFormat  }}
              </td>
              <td>
                <span v-html="toPay(item)"></span>
              </td>
              <td width= '10%'>
                {{ item.lastSettledOn }}
              </td>
              <td>
                <button
                  @click.prevent="vendorSettlement(item)"
                  title="Settle Now"
                  class="btn btn-warning btn-ajax btn-link"
                >
                  <i class="material-icons">done </i> Settle
                </button>
                <button
                  @click.prevent="addMoreFund(item.vendorId, item.businessName)"
                  title="Add Advance Payment"
                  class="btn btn-success btn-ajax btn-link"              >
                  <i class="material-icons">add </i> Add Fund
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </app-card>
    <!-- Start of Input Modal  -->
  <div class="modal fade" id="addNewModel" tabindex="-1" role="dialog" aria-labelledby="addNewModelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" id="modelClose" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h5 class="modal-title" id="addNewModelModalLabel"></h5>
          </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="vendor_title" class="col-form-label">Vendor*</label>
                    <input type="text" class="form-control" v-model="form.vendor_business_name" id="vendor_business_name" required>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="amount" class="col-form-label">Amount*</label>
                            <span id="amount_to_settle"></span>
                            <input type="number" v-model="form.amount" id="amount" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="remarks" class="col-form-label">Remarks*</label>
                            <input type="text" v-model="form.remarks" class="form-control" id="remarks" required>
                        </div>
                    </div>
                    <p>
                      <small class="modal-note"></small>
                    </p>
                </div>
                <input type="hidden" v-model="form.vendor_id" id="vendor-id" name="vendor_id" value="">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="addData()">Save</button>
        </div>
        </div>
    </div>
  </div>
  <!-- End of input Model -->
  </div>
</template>

<script>
import Form from "@utils/Form";
import moment from "moment";

export default {
  name: "VendorSettlement",

  data() {
    return {
      idx: "",
      data: [],
      columns: [
        "S.N",
        "Vendor",
        "Settle Range",
        "Orders",
        "Order Total",
        "GoGo Total",
        "Advance Balance",
        "To Pay",
        "Last Settled On",
        "Action",
      ],
      form: new Form({
        vendor_id: "",
        vendor_business_name: "",
        amount: "",
        remarks: "",
        vendor_adv_amount: ""
      }),
      filter: '7-days',
      addfund: false
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    loadFilterData: function(type){
      this.filter = type
      this.getData()
    },
    vendorSettlement(item){
      if(item.gogoTotal > 0){
        this.addfund = false;
        this.form.reset();
        this.form.vendor_id = item.vendorId
        this.form.amount = item.gogoTotal
        this.form.vendor_adv_amount = item.vendoAdvanceBalance
        this.form.vendor_business_name = item.businessName
        var modal = $('#addNewModel').modal()
        var text = "Settle Vendor: Payable Amount"
        modal.find('.modal-title').text(text)
        var toPay = item.gogoTotal - item.vendoAdvanceBalance
        if(toPay > 0){
          var note = "Note: You have to pay Rs. "+ toPay +" to settle " + item.totalOrders + " orders worth Rs. "+ item.gogoTotal + " including advance balance of Rs. "+ item.vendoAdvanceBalance
        }else{
          var note = "Note:" + item.totalOrders + " orders worth Rs. "+ item.gogoTotal + " will be settled from advance balance of Rs. "+ item.vendoAdvanceBalance
        }
        modal.find('.modal-note').text(note)
        modal.find('.modal-body #amount_to_settle').text(item.gogoTotal).show()
        modal.find('.modal-body #amount').attr('disabled', 'disabled').hide();
        modal.find('.modal-body #vendor_business_name').attr('disabled', 'disabled');
        modal.show()
      }else{
        swal('Nothing to Settle: Add Fund to Make Advance Payment')
      }
      
    },
    addMoreFund(vendor_id, vendor_business_name){
        this.addfund = true;
        this.form.reset();
        this.form.vendor_id = vendor_id
        this.form.vendor_business_name = vendor_business_name
        var modal = $('#addNewModel').modal()
        var text = "Add Advance Settlement Fund"
        modal.find('.modal-title').text(text)
        modal.find('.modal-body #amount_to_settle').hide()
        modal.find('.modal-body #amount').attr('disabled', false).show();
        modal.find('.modal-body #vendor_business_name').attr('disabled', 'disabled');
        modal.show()        
    },
    addData(){
      swal({
        title: "Are you sure?",
        text: "Once Added, you will not be able to revert the amount!",
        icon: "warning",
        buttons: ["Cancle!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
          if(result){
            if (!this.form.amount || !this.form.vendor_id || !this.form.remarks) {
              swal("Invalid Input")
              throw null;
            } else {
              $('#modelClose').click();
              if(this.addfund){
                var postUrl = '/admin/vendor-advance-settlement/add'
              }else{
                var postUrl = '/admin/vendor-settle/update'
              }
              this.form.post(postUrl)
              .then((response) => {
                if (response.status) {
                  alertMessage(response.message)
                  this.getData();
                  this.form.reset();
                }else {
                  alertMessage("Action cannot be processed.", "danger");
                }
              });
              swal.stopLoading();
              swal.close();          
          }
        }
      });
    },
    toPay(item){
        var total = item.gogoTotal - item.vendoAdvanceBalance;
        if( total >= 0){
          return("<span class='text-success'>Rs "+total + "</span>")
        }else{
          return("<span class='text-danger'>-Rs "+ total* (-1) +"</span>")
        }
    },
    getData() {
      this.data = [];
      axios.get("/admin/vendor-settle/"+this.filter).then((response) => {
        this.data = response.data.data;
      });
    }
  },
  created() {
    this.getData();
  },
  mounted() {},
  computed: {},
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
