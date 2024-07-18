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
              <a class="nav-link" href="#" @click="filterData('this-week')">This Week </a>
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
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-4">
            <!-- Registered Users Chart -->
            <app-card
              :title="'Registered '"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'" width="400" height="400">
              <div id="registered-doughnut-container">
                <canvas id="registeredTypeChart"></canvas>     
              </div>
            </app-card>
          </div>
          <div class="col-md-4">
            <!-- Revenue from Orders and Trips -->
            <app-card
              :title="'Revenue'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
              <div id="revenue-bar-container">
                <canvas id="revenueChart"></canvas>  
              </div>
            </app-card>
          </div>
          <div class="col-md-4">
            <!-- Revenue from Orders and Trips -->
            <app-card
              :title="'Trips'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
              <div id="trips-radar-container">
                <canvas id="tripsChart"></canvas>  
              </div>
            </app-card>
          </div>
        </div>
        
      </div>
      <div class="col-md-3">
        <app-card
            :title="'Quick Links'"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
          <ul class="list-group-flush list-group">
            <li v-for="item in quickLinks" :key="item.name" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" ><!----><!---->
              <router-link :to="item.url ">
                  {{item.name}}  
                </router-link>                          
              <span class="pull-right badge success-color badge-pill" >
                {{item.title}}
              </span>
            </li>                  
          </ul>
        </app-card>
      </div>
    </div>
    <!-- end of first section -->
    <hr>
    <!-- Start of second section -->
    <div class="row">
      <div class="col-md-9">
        <app-card
          title="Top 5 Trending Products"
          v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
        >
          <div class="table-responsive">
            <table id="trendingProductsTable" class="table table-hover table-striped table-bordered table-lg" width="100%">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Vendor</th>
                  <th>Sales Qty</th>
                  <th>Unit Price</th>
                  <th>Selling Price</th>
                  <th>Revenue</th>
                </tr>
              </thead>
            </table>
          </div>
        </app-card>
      </div>
      <div class="col-md-3">
             
      </div>
      
    </div>



  </div>
</template>

<script>
import Report from "@utils/models/Reports";
import { mapGetters } from "vuex";
import { REPORT_HOME } from "@routes/admin";

export default {
  name: "ReportIndex",
  data() {
    return {
      quickLinks: [],
      webDataCounts: [],
      model: new Report(),
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
      filter: 'today',
      user_pie_data: [],
      revenueData: [],
      tripsData:[],
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

      trendingProductsData:[],

    };
  },

  methods: {
    filterData: function(type){
      this.from = ''
      this.to = ''
      this.filter = type
      this.loadData()
    },  
    // Updating Pie Chart
    userDoughnutData: function(){
      $('#registeredTypeChart').remove(); // this is my <canvas> element
      $('#registered-doughnut-container').append('<canvas id="registeredTypeChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('registeredTypeChart');
      const registeredTypeChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['App User', 'Vendors', 'Drivers'],
          datasets: [{
              label: this.filter,
              data: this.user_pie_data,
              backgroundColor: this.backgroundColor,
              borderColor: this.borderColor,
              borderWidth: 1
          }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              },
              title: {
                display: false,
                text: this.filter.replace(/-/g,' ')
              }
            }
          },
      });
    }, 
    // Revenue Bar chart
    revenueBarData: function(){
      $('#revenueChart').remove(); // this is my <canvas> element
      $('#revenue-bar-container').append('<canvas id="revenueChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('revenueChart');
      const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Orders', 'Trips'],
          datasets: [{
              label: this.filter.replace(/-/g,' '),
              data:  this.revenueData,
              backgroundColor: this.backgroundColor,
              borderColor: this.borderColor,
              borderWidth: 1
          }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              },
              title: {
                display: false
              }
            }
          },
      });
    },
    // Trips Status Radar table
    tripsRaderData: function(){
      $('#tripsChart').remove(); // this is my <canvas> element
      $('#trips-radar-container').append('<canvas id="tripsChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('tripsChart');
      const revenueChart = new Chart(ctx, {
        type: 'radar',
        data: {
          labels: ['Completed','Ongoing','Accident', 'Dispute'],
          datasets: [{
              label: this.filter.replace(/-/g,' '),
              data:  this.tripsData,
              backgroundColor: this.backgroundColor,
              borderColor: this.borderColor,
              borderWidth: 1
          }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              },
              title: {
                display: false
              }
            }
          },
      });
    },

    // Updating Trending Product DataTable
    trendingProductsDataTable: function(){
      if ($.fn.DataTable.isDataTable( '#trendingProductsTable' ) ) {
        $("#trendingProductsTable").DataTable().destroy()
      }
      $("#trendingProductsTable").DataTable({
        buttons: false,
        paging: false,
        searching: false,
        data: this.trendingProductsData,          
        columns: [
          { data: 'name'},
          { data: 'vendor'},
          { 
            data: 'totalQty'
          },
          { 
            data: 'productPrice'
          },
          { 
            data: 'avgSellPrice'
          },
          { 
            data: 'totalRev'
          },
          // { data: 'avgPrice',
          //   render: function ( data, type, row ) {
          //       return data/100;                  
          //   } 
          // },
          // { 
          //   data: 'product',
          //   render: function ( data, type, row ) {
          //       return data.price-data.discount/100;                  
          //   } 
          // },
          // { 
          //   data: 'vendor',
          //   render: function ( data, type, row ) {
          //       return data.business_name;
          //   }
          // }
        ]
      })
    },
    // End of Trending Product DataTable

    reloadCounts($event) {
      $event.target.classList.add("fa-spin");
      location.href = REPORT_HOME;
    },
    // SideBar Quick Links
    listItems(){
      this.quickLinks = [
        {
          name: "App Users",
          title: this.webDataCounts.users,
          url: { name: "app-users-report.index" , params:{filter:this.filter} },
        },
        {
          name: "Vendors",
          title: this.webDataCounts.vendors,
          url: { name: "vendors-report.index" , params:{filter:this.filter} },
        },
        {
          name: "Drivers",
          title: this.webDataCounts.drivers,
          url: { name: "drivers-report.index", params:{filter:this.filter} },
        },
        {
          name: "Orders",
          title: this.webDataCounts.orders,
          url: { name: "orders-report.index", params:{filter:this.filter} },
        },
        {
          name: "Trips Completed",
          title: this.webDataCounts.trips_completed,
          url: { name: "trips-report.index", params:{filter:this.filter} },
        }
      ];
    },
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        let q = 'custom'
        axios.get(this.model.indexUrl+"/report-dashboard?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
          this.webDataCounts = response.data.data;
          this.user_pie_data = [this.webDataCounts.users,this.webDataCounts.vendors, this.webDataCounts.drivers]
          this.revenueData = [this.webDataCounts.order_revenue/100,this.webDataCounts.trip_revenue]
          this.tripsData = this.webDataCounts.trips
          this.trendingProductsData = this.webDataCounts.trending_products
          this.userDoughnutData()
          this.revenueBarData()
          this.tripsRaderData()
          this.trendingProductsDataTable()
          this.listItems()
        });
      }
    },
    loadData: function(){
      let q = this.filter
      axios.get(this.model.indexUrl+'/report-dashboard?q='+q).then((response) => {
        this.webDataCounts = response.data.data;
        this.user_pie_data = [this.webDataCounts.users,this.webDataCounts.vendors, this.webDataCounts.drivers]
        this.revenueData = [this.webDataCounts.order_revenue/100,this.webDataCounts.trip_revenue]
        this.tripsData = this.webDataCounts.trips
        this.trendingProductsData = this.webDataCounts.trending_products
        this.userDoughnutData() 
        this.revenueBarData() 
        this.tripsRaderData()     
        this.trendingProductsDataTable() 
        this.listItems()
      });
    }

  },

  computed: {
    ...mapGetters(["homePageCounts", "authUser"]),
    
  },

  mounted() {

    if (this.$route.params[0]) {
      alertMessage("404, Page not found !!!", "danger");
    }
  },
  created() {
    this.loadData(); //load the user in the table
  }
};
</script>
