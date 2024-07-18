<template>
  <div class="card card-stats">
    <div
      class="card-header"
      :data-background-color="info.backgroundColor || 'blue'"
    >
      <i class="material-icons">{{ info.icon || "person" }}</i>
    </div>
    <router-link
      v-if="info.accident"
      class="card-footer"
      :class="{ 'cursor-pointer hover-effect': info.url }"
      tag="div"
      :to="{
        name: 'trip.index',
        params: { state: info.accident ? 'accident' : '' },
      }"
    >
      <div class="card-header" :data-background-color="'red'" title="Accident">
        <span class="material-icons">notifications</span>
        <span class="notification">{{ info.accident }}</span>
      </div>
    </router-link>

    <router-link
      v-if="info.pending"
      class="card-footer"
      :class="{ 'cursor-pointer hover-effect': info.url }"
      tag="div"
      :to="{
        name: 'order.index',
        params: { active: info.pending ? 'PENDING' : '' },
      }"
    >
      <div
        class="card-header"
        :data-background-color="'green'"
        title="Pending Orders"
      >
        <span class="material-icons">notifications</span>
        <span class="notification">{{ info.pending }}</span>
      </div>
    </router-link>

    <div class="card-content">
      <p class="category">{{ info.name }}</p>
      <h3 class="card-title">{{ info.title | commaNumberFormat }}</h3>
    </div>
    <router-link
      class="card-footer"
      :class="{ 'cursor-pointer hover-effect': info.url }"
      tag="div"
      :to="info.url || {}"
    >
      <div class="stats">
        <i class="material-icons">{{ info.footerIcon || "date_range" }}</i>
        {{ info.footerText || "From the beginning" }}
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  name: "CardSimple",

  data() {
    return {
      info: {
        backgroundColor: "",
        icon: "",
        name: "",
        title: "",
        pending: 0,
        accident: "",
        footerIcon: "",
        footerText: "",
      },
    };
  },

  props: {
    pInfo: {
      type: Object,
      required: true,
    },
  },

  created() {
    this.info = this.pInfo;

    if (this.info.name === "Orders") {
      var pendingOrdersCount = firebase.database().ref("pending");
      pendingOrdersCount.on("value", (snapshot) => {
        const data = snapshot.val();
        if (data) {
          this.info.pending = data;
        }
      });
    }
  },
};
</script>

<style scoped
       lang="scss">
.cursor-pointer {
  cursor: pointer;
}

.card {
  .card-footer {
    margin: 0;
    padding: 10px 20px;

    &.hover-effect:hover {
      background-color: rgba(#0087cb, 0.1);
    }
  }
}
</style>