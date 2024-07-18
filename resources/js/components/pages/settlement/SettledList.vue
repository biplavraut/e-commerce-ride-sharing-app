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
    <hr>
    <div style="margin-bottom:10px; display:flex;">
      <ul class="nav nav-pills nav-pills-default">
          <li :class="filterdays === '7-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('7-days')">7 Days </a>
          </li>
          <li :class="filterdays === '15-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('15-days')">15 Days </a>
          </li>
          <li :class="filterdays === '30-days' ? 'active' : 'nav-item'">
              <a class="nav-link" href="#" @click="loadFilterData('30-days')">30 Days </a>
          </li>    
      </ul>
    </div>
    <app-card title="List <b>Vendors Settled</b>" body-padding="0">
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
              </td>
              <td width="10%">
                <span class="badge">{{ item.phone }}</span> /
                <span class="badge">{{ item.email }}</span>
              </td>
              <td>
                Rs. {{ item.amount | commaNumberFormat }}
              </td>
              <td>
                {{ item.log }}
              </td>
              <td>
                {{ formatDate(item.settledOn) }}
              </td>
              <td>
                {{ item.settledBy }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </app-card>
  </div>
</template>

<script>
import moment from "moment";

export default {
  name: "VendorSettled",

  data() {
    return {
      idx: "",
      data: [],
      columns: [
        "S.N",
        "Vendor",
        "Contact",
        "Amount",
        "Remarks",
        "Settled On",
        "Settled By",
      ],
      from:'',
      to:'',
      filter_from:'',
      filter_to:'',
      filter: 'today',
      filterdays: '7-days',
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    filterData: function(type){
      this.from = ''
      this.to = ''
      this.filter = type
      this.getData()
    },  
    loadFilterData: function(type){
      this.filterdays = type
      this.getData()
    },
    checkDate: function(){
      if(this.from != '' && this.to != ''){
        this.filter = 'custom'
        this.filter_from = this.from
        this.filter_to = this.to
        this.getData()
      }
    },
    getData() {
      this.data = [];
      axios.get("/admin/vendor-settled-report?days="+this.filterdays+"&filter="+this.filter+"&from="+this.from+"&to="+this.to).then((response) => {
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
