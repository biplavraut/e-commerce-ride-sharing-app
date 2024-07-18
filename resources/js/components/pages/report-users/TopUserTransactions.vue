<template>
  <div>
    <div style="margin-bottom:10px; display:flex;">
      <ul class="nav nav-pills nav-pills-warning">
          <li :class="filter === 'today' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('today')">Today </a>
          </li>
          <li :class="filter === 'yesterday' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('yesterday')">Yesterday </a>
          </li>
          <li :class="filter === 'this-week' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('this-week')">This Week</a>
          </li>
          <li :class="filter === 'this-month' ? 'active' : 'nav-item'">
              <a aria-current="true" href="#" @click="filterData('this-month')">This Month </a>
          </li>
          <li :class="filter === 'custom' ? 'active' : 'nav-item'">
              <a aria-current="true" href="#" aria-disabled="disabled" title="Please Select Date">Custom </a>
          </li>        
      </ul>
      <div class="input-group">
        <span class="input-group-addon" id="date-addon1"> <strong>From</strong> </span>
        <input type="date" class="form-control" id='datetimepicker6' v-model="from" @input="checkDate" aria-describedby="basic-addon1">
      </div>
      <div class="input-group">
        <span class="input-group-addon" id="date-addon2"><strong>To</strong> </span>
        <input type="date" class="form-control" id='datetimepicker7' v-model="to" @input="checkDate" aria-describedby="basic-addon2">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <app-card
          :title="'Top User Transactions '"
          v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
        >
          <div class="table-responsive">
            <table id="myDataTable" class="table table-hover table-striped table-bordered table-lg" width="100%">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Registered</th>
                  <th>Orders</th>
                  <th>Trips</th>
                  <th>Utilities </th>
                  <th>Total Transactions</th>
                </tr>
              </thead>
            </table>
          </div>
        </app-card>
      </div>      
    </div>


  </div>
</template>

<script>
import Report from "@utils/models/Reports";
import moment from "moment";
import { mapGetters } from "vuex";
import { REPORT_HOME } from "@routes/admin";

export default {
  name: "ReportIndex",

  data() {
    return {
      filteredDataTable: [],
      dataTable:[],
      userTransactionsData:[],
      search: "",
      usersList: [],
      userId: this.$route.params.userId ?? 0,
      userFullName: '',
      userPhone:'',
      model: new Report(),
      filter: 'today',
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
    };
  },

  methods: {
    // Filter Data on basis of time
    filterData(type){
      this.from = ''
      this.to = ''
      this.filter = type
      this.loadData()        
    },
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        let q = 'custom'
        axios.get(this.model.indexUrl+"/top-user-transactions?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
            this.userTransactionsData = response.data.data;
            this.userFullName = response.data.first_name +' '+ response.data.last_name ;
            this.userPhone = response.data.phone 
            $("#myDataTable").DataTable().destroy()
            this.transactionDataTable()
        });
      }
    },
    // Initial Load of data
    loadData: function(){
      let q = this.filter
      axios.get(this.model.indexUrl+"/top-user-transactions?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
        this.userTransactionsData = response.data.data;
        this.userFullName = response.data.first_name +' '+ response.data.last_name ;
        this.userPhone = response.data.phone
        this.transactionDataTable() // Function for Data Preparation
      });
    },
    // End of Initial Load of Data
    displayTransactions: function(userId){
        this.userId = userId
        let q = this.filter
        axios.get(this.model.indexUrl+"/top-user-transactions?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
            this.userTransactionsData = response.data.data;
            this.userFullName = response.data.first_name +' '+ response.data.last_name ;
            this.userPhone = response.data.phone
            $("#myDataTable").DataTable().destroy()
            this.transactionDataTable() // Function for Data Preparation
        });
    },
    // Updating DataTable
    transactionDataTable: function(){
      if ($.fn.DataTable.isDataTable( '#myDataTable' ) ) {
        $("#myDataTable").DataTable().destroy()
      }
      $("#myDataTable").DataTable({
        dom: 'Bfrtip',
        // buttons: ['excel','pdf'],
        buttons: ['excel',
            {
                text: 'PDF',
                extend: 'pdfHtml5',
                message: '',
                orientation: 'potrait',
                exportOptions: {
                  columns: ':visible'
                },
            customize: function (doc) {
                var today = new Date();
                var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
                doc.pageMargins = [40,60,60,40];
                doc.defaultStyle.fontSize = 7;
                doc.styles.tableHeader.fontSize = 10;
                doc.styles.title.fontSize = 12;
                doc.styles.title.alignment = 'left';
                // Remove spaces around page title
                doc.content[0].text = doc.content[0].text.trim();
                // Create a Header
                doc['header']=(function(page, pages) {
                    return {
                        columns: [
                          'GOGO20 | HIGHLY CONFIDENTIAL | ',   
                          {
                            text : 'FOR AUTHORIZED CIRCULATIONS ONLY',
                            fontSize : 8, 
                          },                       
                          {
                            // This is the right column
                            alignment: 'right',
                            text: ['Date: ', { text: date}],
                            color: '#000',
                            background: '#fff',
                            fontSize: 8
                          },
                        ],
                        margin: [40,40],
                        color: '#ad0b00',
                        fontSize : 10,
                    }
                });
                // Styling the table: create style object
                var objLayout = {};
                // Horizontal line thickness
                objLayout['hLineWidth'] = function(i) { return .5; };
                // Vertikal line thickness
                objLayout['vLineWidth'] = function(i) { return .5; };
                // Horizontal line color
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                // Vertical line color
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                // Left padding of the cell
                objLayout['paddingLeft'] = function(i) { return 4; };
                // Right padding of the cell
                objLayout['paddingRight'] = function(i) { return 4; };
                // Inject the object in the document
                doc.content[1].layout = objLayout;
            }
            }
        ],
        pageLength: 20,
        data: this.userTransactionsData,          
        columns: [
          { data: 'name'},
          { data: 'phone', sortable:false,},
          { 
            data: 'registered_at', sortable:false,
            render: function ( data, type, row ) {
                return moment(data).format("LL");
            }
          },
          { data: 'orders'},
          { data: 'trips'},
          { data: 'utility'},
          { data: 'total' }
        ],
        "order": [[ 6, "desc" ]]
      })
    },
    // End of Updating DataTable
  },
  computed: {
    ...mapGetters(["authUser","homePageCounts"]),
  },
  mounted() {
    if (this.$route.params[0]) {
      alertMessage("404, Page not found !!!", "danger");
    }
    
  },
  created() {
      if(this.userId != 0){
              this.loadData();
      }
  }
};
</script>
<style>
div.dataTables_wrapper div.dataTables_filter {
    text-align: left !important;
}
div.dataTables_wrapper div.dataTables_filter input {
    width: 55em !important;
}
div.dataTables_wrapper div.dt-buttons {
  float: right;
}
div.dataTables_wrapper div.dt-buttons button {
    margin: 0px 10px !important;
}
.buttons-excel{
  background-color: #1f6e43 !important;
}
.buttons-pdf{
  background-color: #ad0b00 !important;
}
.table td {
   text-align: center;   
}
.table th {
   height: 40px !important;
}
/* table.fixedHeader-floating{position:fixed !important;background-color:white}table.fixedHeader-floating.no-footer{border-bottom-width:0}table.fixedHeader-locked{position:absolute !important;background-color:white}@media print{table.fixedHeader-floating{display:none}} */
</style>
