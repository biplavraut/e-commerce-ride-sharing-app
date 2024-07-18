<template>
  <app-card :title="'All <b>' + title + '</b>'" body-padding="0">
    <ul class="nav nav-pills nav-pills-warning" style="padding: 5px">
      <li :class="state === 'all' ? 'active' : ''" @click.prevent="reset">
        <a href="#all" data-toggle="tab" aria-expanded="true"
          >onGoing
          <sup>
            <span class="badge" v-if="counts.ongoing > 0">{{
              counts.ongoing
            }}</span>
          </sup>
        </a>
      </li>
      <li
        @click.prevent="scheduled"
        :class="state === 'schedule' ? 'active' : ''"
      >
        <a href="#schedule" data-toggle="tab" aria-expanded="true"
          >Scheduled
          <sup>
            <span class="badge" v-if="counts.schedule > 0">{{
              counts.schedule
            }}</span>
          </sup>
        </a>
      </li>
      <li
        @click.prevent="accidental"
        :class="state === 'accident' ? 'active' : ''"
      >
        <a href="#accident" data-toggle="tab" aria-expanded="true"
          >Accident
          <sup>
            <span class="badge" v-if="counts.accident > 0">{{
              counts.accident
            }}</span>
          </sup>
        </a>
      </li>
      <li
        @click.prevent="disputed"
        :class="state === 'dispute' ? 'active' : ''"
      >
        <a href="#dispute" data-toggle="tab" aria-expanded="true"
          >Disputed
          <sup>
            <span class="badge" v-if="counts.dispute > 0">{{
              counts.dispute
            }}</span>
          </sup>
        </a>
      </li>
      <li @click.prevent="pause" :class="state === 'pause' ? 'active' : ''">
        <a href="#paused" data-toggle="tab" aria-expanded="true"
          >Paused
          <sup>
            <span class="badge" v-if="counts.pause > 0">{{
              counts.pause
            }}</span>
          </sup>
        </a>
      </li>
      <li
        @click.prevent="completedTrip"
        :class="state === 'completed' ? 'active' : ''"
      >
        <a href="#complete" data-toggle="tab" aria-expanded="true"
          >Completed
          <sup>
            <span class="badge" v-if="counts.completed > 0">{{
              counts.completed
            }}</span>
          </sup>
        </a>
      </li>
    </ul>

    <app-table-sortable
      v-if="!accident && !dispute && !schedule"
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/trip/get-data?name='"
      :word="keyword"
    >
      <template slot-scope="{ row }">
        <td>{{ row.tripId }}</td>
        <td :title="row.user.countryCode + ' ' + row.user.phone">
          <small
            >{{ row.user.firstName }} {{ row.user.lastName }}
            <small>({{ row.user.phone }})</small></small
          >
        </td>
        <td>
          <small
            v-if="row.rider"
            :title="row.rider.country_code + ' ' + row.rider.phone"
          >
            {{ row.rider.name }}
            <small>({{ row.rider.phone }})</small>
          </small>
          <small v-else>-</small>
        </td>
        <td @click="fullAddress(row.from, row.to)">
          {{ row.from.substring(0, 20) }} ... / {{ row.to.substring(0, 20) }}...
        </td>
        <td>
          <small class="badge">{{ row.status }}</small>
          <span v-if="row.status == 'completed'">
            <small v-if="suspecious(row.completedAt, row.createdAt, row.duration)" class="badge bg-danger">{{ suspecious(row.completedAt, row.createdAt, row.duration) }}</small>
            <small v-if="delayed(row.completedAt, row.createdAt, row.duration)" class="badge bg-warning">{{ delayed(row.completedAt, row.createdAt, row.duration) }}</small>
          </span>
        </td>
        <td>
          {{ row.paymentMode }}
          <small class="badge" title="Charge(NRs.)">{{ row.charge }}</small>
        </td>
        <td>{{ row.distance }} / {{ row.duration }}</td>

        <td>
          {{ row.log ? row.log + " | By:" + row.cancelledBy : "NO" }}
        </td>
        <td>
          <small>{{ formatDate(row.createdAt) }}</small>
        </td>
        <td>
          <small>{{
            row.completedAt ? formatBookedDate(row.completedAt) : "-"
          }}</small>
        </td>
        

        <td>
          <div class="row">
            <div class="col-md-6" v-if="row.status != 'completed'">
              <app-actions
                @deleteItem="deleteModel"
                :actions="{
                  delete: row.id,
                }"
                title="Cancel"
              ></app-actions>
            </div>
            <div
              class="col-md-6"
              v-if="authUser.email === 'biplavraut@gmail.com'"
            >
              <button
                @click="locationRider(row)"
                type="button"
                title="Locate Them"
                class="btn btn-success btn-ajax"
              >
                <i class="material-icons">location_searching</i>
              </button>
            </div>
          </div>
        </td>
      </template>
    </app-table-sortable>

    <app-table-sortable
      v-if="schedule"
      :columns="schColumns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/trip/get-data?name='"
      :word="keyword"
    >
      <template slot-scope="{ row }">
        <td>{{ row.tripId }}</td>
        <td :title="row.user.countryCode + ' ' + row.user.phone">
          <small
            >{{ row.user.firstName }} {{ row.user.lastName }}
            <small>({{ row.user.phone }})</small></small
          >
        </td>
        <td>
          <small
            v-if="row.rider"
            :title="row.rider.country_code + ' ' + row.rider.phone"
          >
            {{ row.rider.name }}
            <small>({{ row.rider.phone }})</small>
          </small>
          <small v-else>-</small>
        </td>
        <td @click="fullAddress(row.from, row.to)">
          {{ row.from.substring(0, 20) }} ... / {{ row.to.substring(0, 20) }}...
        </td>
        <td>
          <small class="badge">{{ row.status }}</small>
        </td>
        <td>
          {{ row.paymentMode }}
          <small class="badge" title="Charge(NRs.)">{{ row.charge }}</small>
        </td>
        <td>{{ row.distance }} / {{ row.duration }}</td>

        <td>
          {{ row.log ? row.log + " | By:" + row.cancelledBy : "NO" }}
        </td>

        <td>
          <small>{{
            row.completedAt ? formatBookedDate(row.completedAt) : "-"
          }}</small>
        </td>
        <td>
          <small v-if="row.schedule">
            {{ formatDate(row.schedule.date + " " + row.schedule.time) }}</small
          >
        </td>

        <td width="0">
          <div class="col-md-6">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
          <div class="col-md-6" v-if="authUser.email === 'sunbi.mac@gmail.com'">
            <button
              @click="locationRider(row)"
              type="button"
              title="Locate Them"
              class="btn btn-success btn-ajax"
            >
              <i class="material-icons">location_searching</i>
            </button>
          </div>
        </td>
      </template>
    </app-table-sortable>

    <app-table-sortable
      v-if="dispute"
      :columns="disputeCols"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/trip/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>{{ row.tripId }}</td>
        <td :title="row.user.countryCode + ' ' + row.user.phone">
          <small
            >{{ row.user.firstName }} {{ row.user.lastName }}
            <small>({{ row.user.phone }})</small></small
          >
        </td>
        <td>
          <small
            v-if="row.rider"
            :title="row.rider.country_code + ' ' + row.rider.phone"
          >
            {{ row.rider.name }}
            <small>({{ row.rider.phone }})</small>
          </small>
          <small v-else>-</small>
        </td>
        <td @click="fullAddress(row.from, row.to)">
          {{ row.from.substring(0, 40) }} ... / {{ row.to.substring(0, 40) }}...
        </td>

        <td>
          {{ row.dispute }}
        </td>
        <td>{{ row.cancelledBy }}</td>
        <td>
          <small>{{ formatDate(row.createdAt) }}</small>
        </td>

        <td width="0">
          <div class="col-md-6">
            <button
              @click="solveCase(row.id)"
              type="button"
              title="Mark as Solved"
              class="btn btn-success btn-ajax"
            >
              <i class="material-icons">done</i>
            </button>
          </div>
          <div class="col-md-6">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>
          <div class="col-md-6" v-if="authUser.email === 'sunbi.mac@gmail.com'">
            <button
              @click="locationRider(row)"
              type="button"
              title="Locate Them"
              class="btn btn-success btn-ajax"
            >
              <i class="material-icons">location_searching</i>
            </button>
          </div>
        </td>
      </template>
    </app-table-sortable>

    <app-table-sortable
      v-if="accident"
      :columns="accCols"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/trip/get-data?name='"
      :word="keyword"
    >
      <template slot-scope="{ row }">
        <td>{{ row.tripId }}</td>
        <td :title="row.user.countryCode + ' ' + row.user.phone">
          <small
            >{{ row.user.firstName }} {{ row.user.lastName }}
            <small>({{ row.user.phone }})</small></small
          >
        </td>
        <td>
          <small
            v-if="row.rider"
            :title="row.rider.country_code + ' ' + row.rider.phone"
          >
            {{ row.rider.name }}
            <small>({{ row.rider.phone }})</small>
          </small>
          <small v-else>-</small>
        </td>
        <td @click="fullAddress(row.from, row.to)">
          {{ row.from.substring(0, 40) }} ... / {{ row.to.substring(0, 40) }}...
        </td>
        <td>
          <small>{{ formatDate(row.createdAt) }}</small>
        </td>

        <td>
          <div class="row">
            <div class="col-md-4">
              <button
                @click="solveCase(row.id)"
                type="button"
                title="Mark as Solved"
                class="btn btn-success btn-ajax"
              >
                <i class="material-icons">done</i>
              </button>
            </div>
            <div class="col-md-4">
              <app-actions
                @deleteItem="deleteModel"
                :actions="{
                  delete: row.id,
                }"
              ></app-actions>
            </div>
            <div
              class="col-md-4"
              v-if="authUser.email === 'sunbi.mac@gmail.com'"
            >
              <button
                @click="locationRider(row)"
                type="button"
                title="Locate Them"
                class="btn btn-success btn-ajax"
              >
                <i class="material-icons">location_searching</i>
              </button>
            </div>
          </div>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Trip from "@utils/models/Trip";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "TripIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "tripId",
        "User",
        "Rider",
        "From/To",
        "Status",
        "Payment",
        "Distance/Duration",
        "isCancelled",        
        "Created On",
        "Completed At",
      ],
      disputeCols: [
        "tripId",
        "User",
        "Rider",
        "From/To",
        "Dispute Explain",
        "By",
        "Created On",
      ],
      schColumns: [
        "tripId",
        "User",
        "Rider",
        "From/To",
        "Status",
        "Payment",
        "Distance/Duration",
        "isCancelled",
        "Completed At",
        "Schedule For",
      ],
      title: "onGoing Trips",
      accCols: ["tripId", "User", "Rider", "From/To", "Created On"],
      rows: { data: [], links: {}, meta: {} },
      model: new Trip(),
      accident: false,
      dispute: false,
      schedule: false,
      paused: false,
      completed: false,
      state: "all",
      counts: {
        ongoing: 0,
        accident: 0,
        dispute: 0,
        schedule: 0,
        pause: 0,
        completed: 0,
      },
      keyword: "",
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    formatBookedDate(date) {
      return moment(date).format("LLLL");
    },
    suspecious(completed, created, duration){
      var completedDate = new Date(completed);                
      var createdDate = new Date(created);
      var tripDiff = completedDate.getTime() - createdDate.getTime(); //Actual trip time millisec
      var tripTime =tripDiff/1000;
      // var tripTime = this.time(diff/1000);
      var appTime = this.parseTime(duration); // Estimated time calculated from app
      var actualTime = appTime.split(':');
      var estimatedTime = (+actualTime[0]) * 60 * 60 + (+actualTime[1]) * 60 + (+actualTime[2]); // Estimated time in sec
      if(tripTime < estimatedTime * 0.5){
        // this.tripFlag = 'suspecious';
        return 'Suspecious by '+ this.time((estimatedTime * 0.5)-tripTime);
      }else{
        return false;
      }
    },
    delayed(completed, created, duration){
      var completedDate = new Date(completed);                
      var createdDate = new Date(created);
      var tripDiff = completedDate.getTime() - createdDate.getTime(); //Actual trip time millisec
      var tripTime =tripDiff/1000;
      // var tripTime = this.time(diff/1000);
      var appTime = this.parseTime(duration); // Estimated time calculated from app
      var actualTime = appTime.split(':');
      var estimatedTime = (+actualTime[0]) * 60 * 60 + (+actualTime[1]) * 60 + (+actualTime[2]); // Estimated time in sec
      if(tripTime > estimatedTime * 1.5){
        // this.tripFlag = 'delayed';
        return 'Delayed by '+ this.time(tripTime - (estimatedTime * 0.5));
      }else{
        return false;
      }
    },
    time(num){
      var minute = (num/60);
      if(minute < 60){
        return Math.round(minute) +' mins';
      }else if(minute/60 < 24){
        var hours = (minute / 60);
        var rhours = Math.floor(hours);
        var minutes = (hours - rhours) * 60;
        var rminutes = Math.round(minutes);
        return rhours + " hours & " + Math.round(rminutes) + " mins";
      }
    },
    parseTime( t ) {
      var hrs = t.match(/hours/);
      if(hrs){
        return t.replace(/hours|mins/gi, ':').replace(/\s/g, '')+'0';
      }else{
        return '0:'+t.replace(/mins/, ':').replace(/\s/g, '')+'0';
      }
    },
    async locationRider(row) {
      if (row.rider) {
        let loc = await this.model.getLocationData(row.rider.id);

        this.getLocation(loc.lat, loc.long);
      } else {
        this.getLocation(row.fromLat, row.fromLong);
      }
    },

    getLocation(lat, long) {
      var apikey = "27ddafe76ecf4e9994bb61acf05e0243";
      var latitude = lat;
      var longitude = long;

      var api_url = "https://api.opencagedata.com/geocode/v1/json";

      var request_url =
        api_url +
        "?" +
        "key=" +
        apikey +
        "&q=" +
        encodeURIComponent(latitude + "," + longitude) +
        "&pretty=1" +
        "&no_annotations=1";

      return fetch(request_url)
        .then((resp) => resp.json())
        .then(function (data) {
          var suburb = data.results[0].components.suburb;
          var near = data.results[0].components.neighbourhood;
          var road = data.results[0].components.road;
          var city = data.results[0].components.city;
          var locationIs = "";

          if (road) {
            locationIs += road + ", ";
          }

          if (suburb) {
            locationIs += suburb + ", ";
          }

          if (near) {
            locationIs += "Near to " + near + ", ";
          }

          if (city) {
            locationIs += city;
          }
          swal("They are in", locationIs);
        })
        .catch(function () {
          console.log("error");
        });
    },
    fullAddress(from, to) {
      swal("Full Address", 'From:\n'+ from + "\nTo:\n" + to);
    },
    accidental() {
      this.state = "accident";
      this.accident = true;
      this.dispute = false;
      this.paused = false;
      this.schedule = false;
      this.completed = false;

      this.title = "Accident Trips";
      axios.get("/admin/trip/get-accident-trips").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    disputed() {
      this.state = "dispute";
      this.dispute = true;
      this.accident = false;
      this.schedule = false;
      this.paused = false;
      this.completed = false;

      this.title = "Disputed Trips";
      axios.get("/admin/trip/get-dispute-trips").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    pause() {
      this.state = "pause";
      this.dispute = false;
      this.accident = false;
      this.schedule = false;
      this.paused = true;
      this.completed = false;
      this.title = "Paused Trips";
      axios.get("/admin/trip/get-paused-trips").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    scheduled() {
      this.state = "schedule";
      this.accident = false;
      this.dispute = false;
      this.schedule = true;
      this.paused = false;
      this.completed = false;

      this.title = "Scheduled Trips";
      axios.get("/admin/trip/get-schedule-trips").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    completedTrip() {
      this.state = "completed";
      this.accident = false;
      this.dispute = false;
      this.schedule = false;
      this.paused = false;
      this.completed = true;
      this.title = "Completed Trips";
      axios.get("/admin/trip/get-completed-trips").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    reset() {
      this.state = "all";
      this.dispute = false;
      this.accident = false;
      this.schedule = false;
      this.paused = false;
      this.completed = false;
      this.title = "onGoing Trips";
      axios.get("/admin/trip").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
      });
    },
    solveCase(tripId) {
      axios.get("/admin/trip/solve-case/" + tripId).then((response) => {
        if (response.data === "success") {
          this.reset();
        }
      });
    },
    counter() {
      axios.get("/admin/trip").then((response) => {
        this.counts.ongoing = response.data.meta.total;
      });
      axios.get("/admin/trip/get-accident-trips").then((response) => {
        this.counts.accident = response.data.meta.total;
      });
      axios.get("/admin/trip/get-dispute-trips").then((response) => {
        this.counts.dispute = response.data.meta.total;
      });
      axios.get("/admin/trip/get-schedule-trips").then((response) => {
        this.counts.schedule = response.data.meta.total;
      });
      axios.get("/admin/trip/get-completed-trips").then((response) => {
        this.counts.completed = response.data.meta.total;
      });
      axios.get("/admin/trip/get-paused-trips").then((response) => {
        this.counts.pause = response.data.meta.total;
      });
    },
  },

  mounted() {
    this.getModels();
    this.counter();
  },
  created() {
    if (this.$route.params.state == "accident") {
      this.accidental();
    }
    if (this.$route.params.word) {
      this.keyword = this.$route.params.word;
    }
  },
  watch: {},
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
.card-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /* background-color : #337AB7; */
  color: #666666;
}
.bg-danger{
  background-color: #fa5661;
}
.bg-warning{
  background-color: #ff9800;
}
</style>