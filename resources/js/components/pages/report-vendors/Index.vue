<template>
  <div>
    <div style="margin-bottom:10px; display:flex;">
      <ul class="nav nav-pills nav-pills-warning">
          <li :class="filter === 'today' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('today')">Today <span class="badge">{{ thisMonthData.dataToday }}</span></a>
          </li>
          <li :class="filter === 'yesterday' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('yesterday')">Yesterday <span class="badge">{{ thisMonthData.dataYesterday }}</span></a>
          </li>
          <li :class="filter === 'this-week' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="filterData('this-week')">This Week <span class="badge">{{ thisMonthData.dataThisWeek }}</span></a>
          </li>
          <li :class="filter === 'this-month' ? 'active' : 'nav-item'">
              <a aria-current="true" href="#" @click="filterData('this-month')">This Month <span class="badge">{{ thisMonthData.dataThisMonth }}</span></a>
          </li>
          <li :class="filter === 'custom' ? 'active' : 'nav-item'">
              <a aria-current="true" href="#" aria-disabled="disabled" title="Please Select Date">Custom <span class="badge"><span v-if="filter == 'custom'">{{ totalData.dataCustom }}</span><span v-else>0</span></span></a>
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
      <div class="col-md-9">
        <app-card
          title="Registered Vendors List"
          v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
        >
          <template slot="actions">
            <app-btn-link
                background="primary"
                icon="done"
                route-name="vendor.settled"
                >SETTLED REPORT
            </app-btn-link>
          </template>
          <div class="table-responsive">
            <table id="myDataTable" class="table table-hover table-striped table-bordered table-lg" width="100%">
              <thead>
                <tr>
                  <th>Business Name</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Settlement Time</th>
                  <th>Products</th>
                  <th>Registered</th>
                </tr>
              </thead>
            </table>
          </div>
        </app-card>
      </div>
      <div class="col-md-3">
        <app-card  title="Settlement Period"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
            <div id="pie-container">
              <canvas id="vendorSettlementChart" width="400" height="400"></canvas>     
            </div>      
        </app-card>  
        <!-- <app-card  title="Vendor Status"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
          <div id="bar-container">
            <canvas id="vendorStatusChart" width="400" height="600"></canvas>   
          </div>
        </app-card>       -->
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
      thisMonthData:[],
      totalData: [{
          blocked:10,
          active:20,
          inactive:30,
          top:18
        }       

      ],
      model: new Report(),
      filter: this.$route.params.filter ?? 'today',
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
      // For visualization
      barData:[12, 19, 3, 5],
      genderData:[10,12,2],
      backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
      ],
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
    // Data Preparation Function for Time period under a month
    filterTableData: function() {
      switch(this.filter) {
        case 'today':
          this.filter_from = this.homePageCounts.today
          this.filter_to = this.homePageCounts.today_end
          
          break;
        case 'yesterday':
          this.filter_from = this.homePageCounts.yesterday 
          this.filter_to = this.homePageCounts.today 
          break;
        case 'this-week':
          this.filter_from = this.homePageCounts.thisweek.from 
          this.filter_to = this.homePageCounts.thisweek.to
        break;
        case 'this-month':
          this.filter_from = this.homePageCounts.thismonth.from 
          this.filter_to = this.homePageCounts.thismonth.to
          break;
        default:
          this.filteredDataTable = []
      }    
      this.filteredDataTable = this.thisMonthData.tableData // Replacing Custom data with this month data: if Exist
      // Functions for Data transformation and Visualization
      this.tableDataFilter()
      this.changeGenderData()
      // this.changeStatusData()
    },
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        let q = 'custom'
        axios.get(this.model.indexUrl+"/vendor-report?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
          this.totalData = response.data.data;
          this.filteredDataTable = this.totalData.tableData
          $("#myDataTable").DataTable().destroy()
          this.tableDataFilter()
          this.changeGenderData()
          // this.changeStatusData()
        });
      }
    },
    // Initial Load of data
    loadData: function(){
      let q = this.filter
      axios.get(this.model.indexUrl+"/vendor-report?q="+q).then((response) => {
        this.thisMonthData = response.data.data;
        this.totalData = response.data.data;
        this.filterTableData() // Function for Data Preparation
      });
    },
    // End of Initial Load of Data
    // Push Filtered Value into New dataTable on filter
    tableDataFilter: function(){
      this.dataTable = this.filteredDataTable
      // this.dataTable = []
      // this.filteredDataTable.forEach(element => {
      //   if(element.registered_at >= this.filter_from && element.registered_at <= this.filter_to){
      //     this.dataTable.push(element)
      //   }          
      // });
      this.vendorDataTable() // Data Visualization in Data Table
    },
    // End of Pusing Data into dataTable list
    // Updating DataTable
    vendorDataTable: function(){
      if ($.fn.DataTable.isDataTable( '#myDataTable' ) ) {
        $("#myDataTable").DataTable().destroy()
      }
      $("#myDataTable").DataTable({
        dom: 'Bfrtip',
        buttons: ['excel','pdf'],
        pageLength: 20,
        data: this.dataTable,          
        columns: [
          { data: 'businessName'},
          { data: 'fullName'},
          { data: 'email'},
          { data: 'phone'},
          { data: 'settlementTime'},
          { data: 'products'},
          { 
            data: 'registered_at', 
            render: function ( data, type, row ) {
                return moment(data).format("LL");
            }
          },
        ]
      })
    },
    // End of Updating DataTable
    // Check Gender related data
    changeGenderData: function(){
      // Count Number of male, Female, and Others
      if(this.dataTable.length > 0){
        var m = 0; var f = 0;var o = 0;
        this.dataTable.forEach(element =>{
          if (element.settlementTime == '30') {
            m++;
          }
          else if(element.settlementTime == '15'){
            f++;
          }else{
            o++;
          }
        });
        this.genderData = [m,f,o]
      }else{
        this.genderData = [0,0,0]
      }
      // End of count
      // Refreshing the Pie Chart of Gender
      this.vendorPieData()
    },
    // Updating Pie Chart
    vendorPieData: function(){
      $('#vendorSettlementChart').remove(); // this is my <canvas> element
      $('#pie-container').append('<canvas id="vendorSettlementChart" width="400" height="400"><canvas>');
      var ctx = document.getElementById('vendorSettlementChart');
      const vendorSettlementChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['30 Days', '15 Days', '7 days'],
          datasets: [{
              label: this.filter,
              data: this.genderData,
              backgroundColor: this.backgroundColor,
              borderColor: this.borderColor,
              borderWidth: 1
          }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Vendor Settlement Period: '+ this.filter.replace(/-/g,' ')
              }
            }
          },
      });
    },
    // Check for vendor Status related data
    changeStatusData: function(){
      // Count Number of 'Blocked', 'Active', 'Inactive', 'Top'
      if(this.dataTable.length > 0){
        var b = 0; var a = 0; var i = 0; var t =0;
        this.dataTable.forEach(element =>{
          if (element.blocked == 1) {
            b++;
          }
          if(element.last_login_at >= this.homePageCounts.a_month_period ){
            a++;
          }
          if(element.last_login_at <= this.homePageCounts.a_month_period){
            i++;
          }
          if(element.reward_point > 0){
            t++;
          }
        });
        this.barData = [b,a,i,t]
      }else{
        this.barData = [0,0,0,0]
      }
      // End of count
      // Refreshing the Bar Chart of vendor Status
      this.vendorBarData()
    },
    // Updating Bar Chart
    vendorBarData: function(){
      $('#vendorStatusChart').remove(); // this is my <canvas> element
      $('#bar-container').append('<canvas id="vendorStatusChart" width="400" height="600"><canvas>');
      var ctx = document.getElementById('vendorStatusChart');
      const vendorStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Blocked', 'Active', 'Inactive', 'Top'],
          datasets: [{
              label: this.filter.replace(/-/g,' '),
              data: this.barData,
              backgroundColor: this.backgroundColor,
              borderColor: this.borderColor,
              borderWidth: 1
          }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Vendor Status '
              }
            }
          },
      });
    }
    // End of Updating Bar Chart
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
    this.loadData(); //Request the data
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
