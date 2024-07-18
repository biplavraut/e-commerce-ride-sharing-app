<template>
  <app-card title="All <b>onGoing Deliveries</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/delivery/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td title="Order no">
          {{ row.orderNo }}
        </td>
        <td>
          <small
            >{{ row.order.orderBy }} <br />
            <small class="badge">({{ row.order.phone }})</small></small
          >
        </td>
        <td>
          <small
            v-if="row.driver"
            :title="row.driver.countryCode + ' ' + row.driver.phone"
          >
            {{ row.driver.firstName }} {{ row.driver.lastName }} <br />
            <small class="badge">({{ row.driver.phone }})</small>
          </small>
          <small v-else>-</small>
        </td>
        <td :title="row.order.vendor.email">
          <small
            >{{ row.order.vendor.businessName }}
            <br />
            <small class="badge">( {{ row.order.vendor.phone }})</small></small
          >
        </td>
        <td @click="fullAddress(row.from, row.to)">
          {{ row.from.substring(0, 40) }} ... / {{ row.to.substring(0, 40) }}...
        </td>
        <td>
          <small class="badge">{{ row.status }}</small>
        </td>

        <td>
          {{ row.logs ? row.logs + " | By:" + row.cancelledBy : "NO" }}
        </td>

        <td>
          <small>{{
            row.deliveredAt ? formatBookedDate(row.deliveredAt) : "-"
          }}</small>
        </td>
        <td>
          <small>{{ formatDate(row.createdAt) }}</small>
        </td>

        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
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
        <td v-else></td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import Delivery from "@utils/models/Delivery";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "DeliveryIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "#",
        "Ordered By",
        "Rider",
        "Vendor",
        "From/To",
        "Status",
        "isCancelled",
        "Delivered At",
        "Created On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Delivery(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    formatBookedDate(date) {
      return moment(date).format("LL");
    },
    async locationRider(row) {
      if (row.driver) {
        let loc = await this.model.getLocationData(row.driver.id);

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
      swal("Full Address", from + " to " + to);
    },
  },

  mounted() {
    this.getModels();
  },
  created() {},
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
</style>