<template>
<div>
  <app-card title="All <b>List Advance Settlement</b>" body-padding="0">
    <template slot="actions">
      <button type="button" class="btn btn-round btn-xs title-right-action btn-success" onclick="window.history.back();"><i class="material-icons">arrow_back</i> Back</button>
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
                {{ item.lastAdvancePaid }}
            </td>
            
            <td>
              Rs.
              {{ item.vendorAdvanceBalance | commaNumberFormat }}
            </td>
            <td width= '10%'>
              {{ item.lastSettledOn }}
            </td>
            <td>
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
                    <label for="vendor_title" class="col-form-label">Vendor</label>
                    <input type="text" class="form-control" v-model="form.vendor_business_name" id="vendor_business_name" required>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="amount" class="col-form-label">Amount</label>
                            <input type="number" :disabled= "form.vendor_id <= 0" v-model="form.amount" id="amount" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="remarks" class="col-form-label">Remarks</label>
                            <input type="text" :disabled= "form.vendor_id <= 0" v-model="form.remarks" class="form-control" id="remarks" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" v-model="form.vendor_id" id="vendor-id" name="vendor_id" value="">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
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
  name: "VendorAdvanceSettlement",

  data() {
    return {
      idx: "",
      data: [],
      columns: [
        "S.N",
        "Vendor",        
        "Last Advance Payment",
        "Vendor Advance Balance",
        "Action",
      ],
      form: new Form({
        vendor_id: "",
        vendor_business_name: "",
        amount: "",
        remarks: ""
      }),
      addfund: false
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    // Add New Vendor Advance Settlement data
    newAdvanceSettlement(){
        this.addfund = false;
        this.form.reset();
        var modal = $('#addNewModel').modal()
        var text = "Add New Advance Settlement"
        modal.find('.modal-title').text(text)
        modal.find('.modal-body #vendor_business_name').attr('disabled', false);
        modal.show()
    },

    // Add More fund in existing vendor with advance settlement data
    addMoreFund(vendor_id, vendor_business_name){
        this.addfund = true;
        this.form.reset();
        this.form.vendor_id = vendor_id
        this.form.vendor_business_name = vendor_business_name
        var modal = $('#addNewModel').modal()
        var text = "Add More Fund"
        modal.find('.modal-title').text(text)
        modal.find('.modal-body #vendor_business_name').attr('disabled', 'disabled');
        modal.show()        
    },

    // Request to add data
    addData(){
      swal({
        title: "Are you sure?",
        text: "Once Added, you will not be able to revert the amount!",
        icon: "warning",
        buttons: ["Cancle!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
        if(result){
          if (!this.form.amount || !this.form.vendor_id) {
          swal("Invalid Input")
          throw null;
          } else {
          $('#modelClose').click();
          this.form.post('/admin/vendor-advance-settlement/add')
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
    getData() {
      this.data = [];
      axios.get("/admin/advancesettlement").then((response) => {
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
