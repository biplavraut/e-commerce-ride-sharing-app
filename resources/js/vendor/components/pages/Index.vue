<template>
  <div>
    <div class="row">
      <div
        class="col-lg-3 col-md-6 col-sm-6"
        v-for="(info, index) in shortInfo"
        :key="index"
      >
        <app-card-simple :p-info="info" data-id="hello"></app-card-simple>
      </div>
    </div>

    <app-card title="This month data">
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
    };
  },

  methods: {
    reloadCounts($event) {
      $event.target.classList.add("fa-spin");
      location.href = ADMIN_HOME;
    },
  },

  computed: {
    ...mapGetters(["homePageCounts", "thisMonthCounts"]),

    labels() {
      return Object.keys(this.thisMonthCounts.users);
    },

    datasets() {
      return [
        {
          label: "Products",
          data: Object.values(this.thisMonthCounts.products),
          backgroundColor: "rgba(54, 162, 235, 0.5)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
          // type           : 'line'
        },
      ];
    },
  },

  mounted() {
    this.shortInfo = [
      {
        icon: "layers",
        name: "Products",
        title: this.homePageCounts.products,
        backgroundColor: "orange",
        footerText: "Recently Updated",
        url: { name: "product.index" },
      },
      {
        icon: "reorder",
        name: "Orders",
        title: this.homePageCounts.orders,
        backgroundColor: "green",
        footerText: "Recently Updated",
        url: { name: "orders.index" },
      },
      {
        icon: "sync_alt",
        name: "Takeaway Orders",
        title: this.homePageCounts.takeawayorders,
        backgroundColor: "blue",
        footerText: "Recently Updated",
        url: { name: "orders.takeaway" },
      },
      {
        icon: "list",
        name: "Dinein Requests",
        title: this.homePageCounts.dinein,
        backgroundColor: "purple",
        footerText: "Recently Updated",
        url: { name: "dinein.index" },
      },
    ];

    if (this.$route.params[0]) {
      alertMessage("404, Page not found !!!", "danger");
    }

    // Echo.private('category')
    //     .listen('YoEvent', (e) => {
    //       console.log(e);
    //     });
  },
};
</script>
