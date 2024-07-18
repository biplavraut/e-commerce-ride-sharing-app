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
      <div class="col-md-8">
        <div class="col">
          <app-card
              :title="'Top Riders'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
            <ul class="list-group-flush list-group">
              <li v-for="item in topriders" :key="item.driver_id" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" ><!----><!---->
                <span>{{item.driver.first_name +' '+ item.driver.last_name }}</span> 
                <span class="badge badge-pill" >
                  {{ numberFormat(item.trip_total) }}
                </span>                        
                <span class="badge badge-pill" >
                  {{item.trips}} trips
                </span>              
              </li>                  
            </ul>
          </app-card>
        </div>
        <div class="col">
          <app-card
                :title="'Ride Request From Location Marker'"
                v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
              <div id="myMapFrom" class="z-depth-1-half map-container" style="height: 300px"></div>
          </app-card>
        </div>
        <div class="col">
          <app-card
            title="Ride Request To Location Marker"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
          >
            <div id="myMapTo" class="z-depth-1-half map-container" style="height: 300px"></div>
          </app-card>
        </div>
      </div>
      <div class="col-md-4">
        <div class="col">
          <app-card
              :title="'Requested Vehicle Type '"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'" width="400" height="400">
              <div id="registered-doughnut-container">
              <canvas id="registeredTypeChart"></canvas>     
              </div>
          </app-card>
        </div>
        <!-- Registered Users Chart -->
        <div class="col">
          <!-- Trip Status -->
          <app-card
              :title="'Trip Status'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
              <div id="trip-status-radar-container">
              <canvas id="tripStatusChart"></canvas>  
              </div>
          </app-card>
        </div>
        <div class="col">
          <!-- Payment Mode -->
          <app-card
              :title="'Preferred Payment Mode'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
              <div id="payment-mode-bar-container">
              <canvas id="paymentModeChart"></canvas>  
              </div>
          </app-card>
        </div>
      </div>
    </div>
    <!-- end of first section -->
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
      topriders: [],
      webDataCounts: [],
      model: new Report(),
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
      filter: this.$route.params.filter ?? 'today',
      order_pie_data: [],
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
      rideRequestsFrom : [],
      rideRequestsTo : [],
    };
  },

  methods: {
    numberFormat(no, budgetType){
      let from = new Intl.NumberFormat("en-IN", {
          style: "decimal",
          currency: budgetType ? budgetType : "NPR",
      }).format(no);
      if (from.includes("NaN")) {
          return 'Rs ' + no;
      }
      return 'Rs ' + from;
    },
    filterData: function(type){
      this.from = ''
      this.to = ''
      this.filter = type
      this.loadData()
    },  
    // Updating Pie Chart
    tripDoughnutData: function(){
      $('#registeredTypeChart').remove(); // this is my <canvas> element
      $('#registered-doughnut-container').append('<canvas id="registeredTypeChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('registeredTypeChart');
      const registeredTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          datasets: [{
                label: this.filter.replace(/-/g,' ').toUpperCase(),
                data: this.webDataCounts.vehicle_type,
                backgroundColor: this.backgroundColor,
                borderColor: this.borderColor,
                borderWidth: 1
            }]
        },
        options: {
            parsing: {
                xAxisKey: 'vehicle_type',
                yAxisKey: 'total_vehicles'
            },
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
    // Revenue Bar chart
    paymentModeData: function(){
      $('#paymentModeChart').remove(); // this is my <canvas> element
      $('#payment-mode-bar-container').append('<canvas id="paymentModeChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('paymentModeChart');
      const paymentModeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          datasets: [{
                label: this.filter.replace(/-/g,' ').toUpperCase(),
                data: this.webDataCounts.payment_mode,
                backgroundColor: this.backgroundColor,
                borderColor: this.borderColor,
                borderWidth: 1
            }]
        },
        options: {
            parsing: {
                xAxisKey: 'payment_mode',
                yAxisKey: 'paid_total'
            },
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
    tripStatusRaderData: function(){
      $('#tripStatusChart').remove(); // this is my <canvas> element
      $('#trip-status-radar-container').append('<canvas id="tripStatusChart" width="100" height="100"><canvas>');
      var ctx = document.getElementById('tripStatusChart');
      const tripStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: [{
                label: this.filter.replace(/-/g,' ').toUpperCase(),
                data: this.webDataCounts.trips_status,
                backgroundColor: this.backgroundColor,
                borderColor: this.borderColor,
                borderWidth: 1
            }]
        },
        options: {
            parsing: {
                xAxisKey: 'status',
                yAxisKey: 'status_total'
            },
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              },
              title: {
                display: false
              }
            }
        }
      });
    },


    reloadCounts($event) {
      $event.target.classList.add("fa-spin");
      location.href = REPORT_HOME;
    },
    // SideBar Quick Links
    listItems(){
      this.topriders = this.webDataCounts.top_riders;
      this.rideRequestsFrom = this.webDataCounts.from_markers;   
      this.rideRequestsTo = this.webDataCounts.to_markers;    
      const myLatlng = { lat: 27.733697229707158, lng: 85.34125707301315 };
      const mapfrom = new google.maps.Map(document.getElementById("myMapFrom"), {
        zoom: 8,
        center: myLatlng,
      });
      this.setMarkersFrom(mapfrom);
      const mapto = new google.maps.Map(document.getElementById("myMapTo"), {
        zoom: 8,
        center: myLatlng,
      });
      this.setMarkersTo(mapto);
    },
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        let q = 'custom'
        axios.get(this.model.indexUrl+"/trip-report?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
          this.webDataCounts = response.data.data;
          this.order_pie_data = [this.webDataCounts.orders,this.webDataCounts.delivered, this.webDataCounts.takeaway,  this.webDataCounts.settled ]
          this.trendingProductsData = this.webDataCounts.top_products
          this.tripDoughnutData()
          this.paymentModeData()
          this.tripStatusRaderData()
          // this.trendingProductsDataTable()
          this.listItems()
        });
      }
    },
    loadData: function(){
      let q = this.filter
      axios.get(this.model.indexUrl+'/trip-report?q='+q).then((response) => {
        this.webDataCounts = response.data.data;
        this.order_pie_data = [this.webDataCounts.orders,this.webDataCounts.delivered,this.webDataCounts.takeaway, this.webDataCounts.settled ]
        this.trendingProductsData = this.webDataCounts.top_products
        this.tripDoughnutData() 
        this.paymentModeData() 
        this.tripStatusRaderData()     
        // this.trendingProductsDataTable() 
        this.listItems()
      });
    },
    setMarkersFrom(map) {
      const image = {
        // url: "/storage/images/placeflag.png",
        url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
        size: new google.maps.Size(20, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 32),
      };
      const shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: "poly",
      };

      for (let i = 0; i < this.rideRequestsFrom.length; i++) {
        const place = this.rideRequestsFrom[i];
        new google.maps.Marker({
          position: { lat: parseFloat(place.from_lat) , lng: parseFloat(place.from_long) },
          map,
          icon: image,
          shape: shape,
          title: place.from,
          zIndex: i+1,
        });
      }
    },
    setMarkersTo(map) {
      const image = {
        // url: "/storage/images/placeflag.png",
        url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
        size: new google.maps.Size(20, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 32),
      };
      const shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: "poly",
      };

      for (let i = 0; i < this.rideRequestsTo.length; i++) {
        const place = this.rideRequestsTo[i];
        new google.maps.Marker({
          position: { lat: parseFloat(place.to_lat) , lng: parseFloat(place.to_long) },
          map,
          icon: image,
          shape: shape,
          title: place.to,
          zIndex: i+1,
        });
      }
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
