<template>
  <div>
    <div v-if="authUser.type !== 'support'">
      <div v-for="(info, index) in shortInfo" :key="index">
        <div :class="(index+1)%3 == 0?'row':''">     
          <div class="col-md-4" >
            <app-card-simple :p-info="info" data-id="hello"></app-card-simple>
          </div>
        </div>
      </div>
    </div>   
    <app-card
      title="This month data"
      v-if="this.authUser.type ==='admin' || this.authUser.type ==='superadmin'"
    >
      <template slot="actions">
        <span
          @click="reloadCounts"
          title="Reload"
          class="fa fa-refresh"
          style="
            right: 15px;
            top: 20px;
            float: right;
            margin-left: 10px;
            position: absolute;
            cursor: pointer;
            font-size: 16px;
          "
        ></span>
      </template>

      <app-chart
        height="200"
        :x-labels="labels"
        :datasets="datasets"
      ></app-chart>
    </app-card>

    <app-card
      title="Today's Top Captains"
      v-if="authUser.type !== 'support' && authUser.type !== 'officer'"
    >
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>Image</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Total Rides</th>
              <th>Collection (NRs.)</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(row, index) in homePageCounts.thisDay.riders"
              :key="index"
              :class="index === 0 ? 'w3-card bg-success' : ''"
            >
              <td>{{ ++index }}</td>
              <td width="100">
                <img
                  :src="row.image"
                  style="width: 50px; height: 50px; border-radius: 50%"
                />
              </td>
              <td>{{ row.name }}</td>
              <td>{{ row.phone }}</td>
              <td>{{ row.totalRides }}</td>
              <td>{{ row.collected | commaNumberFormat }}</td>
            </tr>
            <tr v-if="homePageCounts.thisDay.riders.length === 0">
              <td colspan="6">No data available.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </app-card>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { ADMIN_HOME } from "@routes/admin";

export default {
  name: "AdminIndex",

  /*components: {
      "app-card"       : () => import(/!* webpackChunkName: "./js/components/card" *!/ "../material/cards/Card"),
      "app-card-simple": () => import(/!* webpackChunkName: "./js/components/card-simple" *!/ "../material/cards/CardSimple"),
      "app-chart"      : () => import(/!* webpackChunkName: "./js/components/chart" *!/ "../material/charts/Chart"),
    },*/

  data() {
    return {
      shortInfo: [],
      pendingOrders: 0,
    };
  },

  methods: {
    reloadCounts($event) {
      $event.target.classList.add("fa-spin");
      location.href = ADMIN_HOME;
    },
    showNotification: function (from, align, type, message = null) {
      // var type = ["info", "success", "warning", "danger", "rose", "primary"];

      var color = Math.floor(Math.random() * 6 + 1);
      message = message
        ? message
        : "Welcome to <b>gogo20</b> - Everyday solution.";

      $.notify(
        {
          // icon: "notifications",
          message: message,
        },
        {
          type: type, //type[color]
          timer: 3000,
          placement: {
            from: from,
            align: align,
          },
        }
      );
    },
  },

  computed: {
    ...mapGetters(["homePageCounts", "thisMonthCounts", "authUser"]),

    labels() {
      return Object.keys(this.thisMonthCounts.users);
    },

    datasets() {
      return [
        {
          label: "Users",
          data: Object.values(this.thisMonthCounts.users),
          backgroundColor: "rgba(52, 82, 235, 1)",
          borderColor: "rgba(52, 82, 235, 1)",
          borderWidth: 1,
          // type           : 'line'
        },
        {
          label: "Riders",
          data: Object.values(this.thisMonthCounts.drivers),
          backgroundColor: "rgba(25, 191, 130, 1)",
          borderColor: "rgba(25, 191, 130, 1)",
          borderWidth: 1,
        },
        {
          label: "Vendors",
          data: Object.values(this.thisMonthCounts.vendors),
          backgroundColor: "rgba(212, 19, 237, 1)",
          borderColor: "rgba(212, 19, 237, 1)",
          borderWidth: 1,
          // type           : 'line'
        },
        {
          label: "Categories",
          data: Object.values(this.thisMonthCounts.productcategories),
          backgroundColor: "rgba(208, 237, 19, 1)",
          borderColor: "rgba(208, 237, 19, 1)",
          borderWidth: 1,
        },
        {
          label: "Products",
          data: Object.values(this.thisMonthCounts.products),
          backgroundColor: "rgba(237, 113, 19, 1)",
          borderColor: "rgba(237, 113, 19, 1)",
          borderWidth: 1,
        },
      ];
    },
  },

  mounted() {
    this.shortInfo = [
      {
        icon: "person",
        name: "App Users",
        title: this.authUser.type ==='admin' || this.authUser.type ==='superadmin' ? this.homePageCounts.users : null,
        footerText: "Recently Updated",
        url: { name: "users" },
      },
      {
        icon: "drive_eta",
        name: "Riders",
        title: this.authUser.type ==='admin' || this.authUser.type ==='superadmin' ? this.homePageCounts.drivers : null,
        backgroundColor: "green",
        footerText:
          "Online: " +
          this.homePageCounts.activedrivers[0] +
          " | Unverified: " +
          this.homePageCounts.activedrivers[1],
        url: { name: "driver.index" },
      },
      {
        icon: "business",
        name: "Vendors",
        title: this.authUser.type ==='admin' || this.authUser.type ==='superadmin' ? this.homePageCounts.vendors : null,
        backgroundColor: "#f2f2",
        footerText:
          "Verified: " +
          (this.homePageCounts.vendors -
            this.homePageCounts.unverifiedvendors) +
          " | Unverified: " +
          this.homePageCounts.unverifiedvendors,
        url: { name: "vendor.index" },
      },
      {
        icon: "layers",
        name: "Products",
        title:
          this.homePageCounts.unverifiedproducts + this.homePageCounts.products,
        backgroundColor: "green",
        footerText:
          "Verified: " +
          this.homePageCounts.products +
          " | Unverified: " +
          this.homePageCounts.unverifiedproducts,
        url: { name: "product.index" },
      },
      {
        icon: "list",
        name: "Product Categories",
        title: this.homePageCounts.productcategories,
        backgroundColor: "blue",
        footerText: "Recently Updated",
        url: { name: "product-category.index" },
      },
      {
        icon: "hdr_strong",
        name: "Services",
        title: this.homePageCounts.categories,
        backgroundColor: "#f2f2",
        footerText: "Recently Updated",
        url: { name: "service.index" },
      },
      {
        icon: "wb_iridescent",
        name: "Rides",
        title: this.homePageCounts.trips[4],
        backgroundColor: "#f2f2",
        accident: this.homePageCounts.trips[2],
        footerText:
          "Completed: " +
          this.homePageCounts.trips[0] +
          " | Ongoing: " +
          this.homePageCounts.trips[1],
        url: {
          name: "trip.index",
        },
      },
      {
        icon: "storefront",
        name: "Orders",
        title: this.homePageCounts.orders,
        backgroundColor: "green",
        footerText: "Order Feedback: " +
          this.homePageCounts.orderFeedbacks + " | Order Returns: " + this.homePageCounts.orderReturns,          
        url: { name: "order.index" },
        pending: this.pendingOrders,
      },
      {
        icon: "local_shipping",
        name: "Deliveries",
        title: this.homePageCounts.deliveries[1],
        backgroundColor: "blue",
        footerText: "Delivered: " + this.homePageCounts.deliveries[0],
        url: { name: "delivery.index" },
      },      
      {
        icon: "dining",
        name: "Dine-In Request",
        title: this.homePageCounts.dinein,
        backgroundColor: "blue",
        footerIcon: "local_offer",
        url: { name: "dinein.index" },
      },
      {
        icon: "volunteer_activism",
        name: "Donation",
        title: this.homePageCounts.donations,
        backgroundColor: "green",
        footerText: "Total: "+this.homePageCounts.totalDonation,
        url: { name: "donations" },
      },
      {
        icon: "account_balance_wallet",
        name: "Utility Services",
        title: this.homePageCounts.additionalServices,
        backgroundColor: "#f2f2",
        footerIcon: "local_offer",
        url: { name: "additional-service.index" },
      },
      

      // {
      //   icon: "library_books",
      //   name: "News",
      //   title: this.homePageCounts.news,
      //   backgroundColor: "green",
      //   url: { name: "news.index" },
      // },
    ];

    if (this.$route.params[0]) {
      alertMessage("404, Page not found !!!", "danger");
    }

    var pendingOrdersCount = firebase.database().ref("pending");
    pendingOrdersCount.on("value", (snapshot) => {
      const data = snapshot.val();
      if (data) {
        this.pendingOrders;
      }
    });

    // this.showNotification("top", "center", "success");

    // Echo.private("category").listen("YoEvent", (e) => {
    //   console.log(e);
    // });
  },
};
</script>
