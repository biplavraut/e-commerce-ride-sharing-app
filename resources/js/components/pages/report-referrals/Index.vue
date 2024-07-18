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
          <app-card
            title="App Users from Referrals"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
          >
            <div class="table-responsive">
              <table id="appUsersFromReferrals" class="table table-hover table-striped table-bordered table-lg" width="100%">
                <thead>
                  <tr>
                    <th width="20%">Name</th>
                    <th width="20%">Phone</th>
                    <th>Referred By</th>
                    <th>Referred Code</th>
                    <th>Registered</th>
                  </tr>
                </thead>
              </table>
            </div>
          </app-card>        
      </div>
      <div class="col-md-4">
          <app-card
              :title="'Top 10 App Referrars'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
            <ul class="list-group-flush list-group" style="font-size:13px !important">
              <li v-for="item in topUserReferrar" :key="item.user_id" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" ><!----><!---->
                <span>{{ item.user.first_name+' '+item.user.last_name }}</span> 
                <span class="badge badge-pill" >
                  {{ item.total_referred }} User
                </span>                        
                <span class="badge badge-pill" >
                  {{ item.user.refer_code }}
                </span>              
              </li>                  
            </ul>
          </app-card>
        </div>
    </div>
    <!-- end of first section -->
    <div class="row">
      <div class="col-md-8">
          <app-card
            title="Riders from Referrals"
            v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
          >
            <div class="table-responsive">
              <table id="ridersFromReferrals" class="table table-hover table-striped table-bordered table-lg" width="100%">
                <thead>
                  <tr>
                    <th width="20%">Name</th>
                    <th width="20%">Phone</th>
                    <th>Referred By</th>
                    <th>Referred Code</th>
                    <th>Registered</th>
                  </tr>
                </thead>
              </table>
            </div>
          </app-card>        
      </div>
      <div class="col-md-4">
          <app-card
              :title="'Top 10 Rider Referrars'"
              v-if="authUser.type !== 'support' && authUser.type !== 'officer'">
            <ul class="list-group-flush list-group">
              <li v-for="item in topRiderReferrar" :key="item.driver_id" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" ><!----><!---->
                <span>{{ item.driver.first_name+' '+item.driver.last_name }}</span> 
                <span class="badge badge-pill" >
                  {{ item.total_referred }} Rider
                </span>                        
                <span class="badge badge-pill" >
                  {{ item.driver.refer_code }}
                </span>              
              </li>                  
            </ul>
          </app-card>
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
      topUserReferrar: [],
      topRiderReferrar: [],
      webDataCounts: [],
      model: new Report(),
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
      filter: this.$route.params.filter ?? 'today',
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

      appUsersFromReferralsData:[],
      ridersFromReferralsData:[],
      orderPlaces : [],
      ud: true,
      tur:true,
      rd:true,
      trr:true
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    filterData: function(type){
      this.from = ''
      this.to = ''
      this.filter = type
      this.loadData()
    }, 

    // Updating Trending Product DataTable
    appUsersFromReferralsDataTable: function(){
      if ($.fn.DataTable.isDataTable( '#appUsersFromReferrals' ) ) {
        $("#appUsersFromReferrals").DataTable().destroy()
      }
      $("#appUsersFromReferrals").DataTable({
        
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
        pageLength: 10,
        // data: this.appUsersFromReferralsData, 
        ajax: {
            url: '/admin/report/referral-report/referred-user?q='+this.filter+'&from='+this.from+'&to='+this.to,
            dataSrc: 'referredUser'
        },         
        columns: [
          { data: 'used_by',
            render: function ( data, type, row ) {
                return data.first_name+' '+data.last_name;                  
            } 
          },
          { data: 'used_by',
            render: function ( data, type, row ) {
                return data.phone;                  
            } 
          },
          { data: 'user',
            render: function ( data, type, row ) {
                return data.first_name+' '+data.last_name;                  
            } 
          },
          { data: 'user',
            render: function ( data, type, row ) {
                return data.refer_code;                  
            } 
          },
          { data: 'created_at',
          render: function ( data, type, row ) {
                return moment(data).format("LL");;                  
            } }
        ]
      })
    },
    ridersFromReferralsDataTable(){
      if ($.fn.DataTable.isDataTable( '#ridersFromReferrals' ) ) {
        $("#ridersFromReferrals").DataTable().destroy()
      }
      $("#ridersFromReferrals").DataTable({
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
        pageLength: 10,
        ajax: {
            url: '/admin/report/referral-report/referred-riders?q='+this.filter+'&from='+this.from+'&to='+this.to,
            dataSrc: 'riderFromReferred'
        }, 
        data: this.ridersFromReferralsData,          
        columns: [
          { data: 'used_by',
            render: function ( data, type, row ) {
                return data.first_name+' '+data.last_name;                  
            } 
          },
          { data: 'used_by',
            render: function ( data, type, row ) {
                return data.phone;                  
            } 
          },
          { data: 'driver',
            render: function ( data, type, row ) {
                return data.first_name+' '+data.last_name;                  
            } 
          },
          { data: 'driver',
            render: function ( data, type, row ) {
                return data.refer_code;                  
            } 
          },
          { data: 'created_at',
          render: function ( data, type, row ) {
                return moment(data).format("LL");;                  
            } }
        ]
      })
    },
    // End of Trending Product DataTable

    reloadCounts($event) {
      $event.target.classList.add("fa-spin");
      location.href = REPORT_HOME;
    },
    // SideBar Quick Links
    
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        let q = 'custom'
        // axios.get(this.model.indexUrl+"/referral-report?q="+q+'&from='+this.from+'&to='+this.to).then((response) => {
        //   this.webDataCounts = response.data.data
        //   this.appUsersFromReferralsData = response.data.referredUser
        //   this.topUserReferrar = response.data.topAppReferrars
        //   this.ridersFromReferralsData = response.data.riderFromReferred;
        //   this.topRiderReferrar = response.data.topRiderReferrars
        //   this.appUsersFromReferralsDataTable()
        //   this.ridersFromReferralsDataTable()
        // });
        // axios.get(this.model.indexUrl+'/referral-report/referred-user?q='+q+'&from='+this.from+'&to='+this.to).then((response) => {
        //   this.ud = true
        //   this.appUsersFromReferralsData = response.data.referredUser;
        //   this.appUsersFromReferralsDataTable()
        // });
        axios.get(this.model.indexUrl+'/referral-report/top-user-referrar?q='+q+'&from='+this.from+'&to='+this.to).then((response) => {
          this.topUserReferrar = response.data.topAppReferrars
          this.appUsersFromReferralsDataTable()
        });
        // axios.get(this.model.indexUrl+'/referral-report/referred-riders?q='+q+'&from='+this.from+'&to='+this.to).then((response) => {
        //   this.ridersFromReferralsData = response.data.riderFromReferred;
        //   this.ridersFromReferralsDataTable()
        // });
        axios.get(this.model.indexUrl+'/referral-report/top-rider-referrar?q='+q+'&from='+this.from+'&to='+this.to).then((response) => {
          this.topRiderReferrar = response.data.topRiderReferrars
          this.ridersFromReferralsDataTable()
        });
      }
    },
    loadData: function(){
      let q = this.filter
      // axios.get(this.model.indexUrl+'/referral-report?q='+q).then((response) => {
      //   this.webDataCounts = response.data.data;
      //   this.appUsersFromReferralsData = response.data.referredUser;
      //   this.topUserReferrar = response.data.topAppReferrars
      //   this.ridersFromReferralsData = response.data.riderFromReferred;
      //   this.topRiderReferrar = response.data.topRiderReferrars
      //   this.appUsersFromReferralsDataTable()
      //   this.ridersFromReferralsDataTable()
      // });
      
      // axios.get(this.model.indexUrl+'/referral-report/referred-user?q='+q).then((response) => {
      //   this.appUsersFromReferralsData = response.data.referredUser;
      //   this.appUsersFromReferralsDataTable()
      // });
      axios.get(this.model.indexUrl+'/referral-report/top-user-referrar?q='+q).then((response) => {
        this.topUserReferrar = response.data.topAppReferrars
        this.appUsersFromReferralsDataTable()
      });
      // axios.get(this.model.indexUrl+'/referral-report/referred-riders?q='+q).then((response) => {
      //   this.ridersFromReferralsData = response.data.riderFromReferred;
      //   this.ridersFromReferralsDataTable()
      // });
      axios.get(this.model.indexUrl+'/referral-report/top-rider-referrar?q='+q).then((response) => {
        this.topRiderReferrar = response.data.topRiderReferrars
        this.ridersFromReferralsDataTable()
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
