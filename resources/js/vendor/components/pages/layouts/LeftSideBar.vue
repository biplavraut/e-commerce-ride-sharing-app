<template>
  <div
    class="sidebar"
    data-background-color="black"
    :data-image="backgroundImage"
  >
    <div class="sidebar-wrapper">
      <div class="user">
        <div class="photo">
          <img :src="authUser.image" />
        </div>

        <div class="info">
          <a
            data-toggle="collapse"
            href="#collapseExample"
            class="collapsed"
            :aria-expanded="isUserNav"
          >
            <span>
              {{ authUser.name }}
              <b class="caret"></b>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse" :class="{ in: isUserNav }" id="collapseExample">
            <ul class="nav">
              <li
                :class="{ active: $route.name === userNavItem.url.name }"
                v-for="(userNavItem, index) in userNavItems"
                :key="index"
              >
                <router-link :to="userNavItem.url">
                  <span class="sidebar-mini">&nbsp;</span>
                  <span class="sidebar-normal">{{ userNavItem.name }}</span>
                </router-link>
              </li>

              <li>
                <a
                  :href="logoutUrl"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                >
                  <span class="sidebar-mini">&nbsp;</span>
                  <span class="sidebar-normal">Logout</span>
                </a>
                <form
                  id="logout-form"
                  :action="logoutUrl"
                  method="POST"
                  style="display: none"
                >
                  <input type="hidden" name="_token" :value="csrf" />
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <ul class="nav real-left-nav-menus">
        <li
          :class="{
            active: isActive(leftNavItem.url.name),
            'has-drop-down': leftNavItem.children.length > 0,
          }"
          v-for="(leftNavItem, key) in leftNavItems"
          :key="key"
        >
          <router-link
            :to="leftNavItem.url"
            v-if="leftNavItem.children.length === 0"
          >
            <i class="material-icons">{{ leftNavItem.icon }}</i>
            <p>{{ leftNavItem.name }}</p>
          </router-link>

          <a
            v-if="leftNavItem.children.length > 0"
            data-toggle="collapse"
            :href="'#left-sidebar-' + key"
            :aria-expanded="isActive(leftNavItem.url.name)"
          >
            <i class="material-icons">{{ leftNavItem.icon }}</i>
            <p>
              {{ leftNavItem.name }}
              <b class="caret"></b>
            </p>
          </a>
          <div
            v-if="leftNavItem.children.length > 0"
            class="collapse"
            :class="{ in: isActive(leftNavItem.url.name) }"
            :id="'left-sidebar-' + key"
          >
            <ul class="nav">
              <li
                :class="{ active: $route.name === leftNavItemChild.url.name }"
                v-for="(leftNavItemChild, index) in leftNavItem.children"
                :key="index"
              >
                <router-link :to="leftNavItemChild.url">
                  <span class="sidebar-mini">&nbsp;</span>
                  <span class="sidebar-normal">{{
                    leftNavItemChild.name
                  }}</span>
                </router-link>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Common from "./Common";
import { ROOT_URL, LOGOUT_URL } from "@routes/admin";

export default {
  name: "LeftSideBar",

  extends: Common,

  data() {
    return {
      userNavItems: [
        { url: { name: "user.profile" }, name: "My Profile" },
        { url: { name: "user.changePassword" }, name: "Change Password" },
      ],

      leftNavItems: [
        {
          name: "Dashboard",
          icon: "account_balance",
          url: { name: "home" },
          children: [],
        },
        {
          name: "Product",
          icon: "layers",
          url: { name: "product" },
          children: [
            { url: { name: "product.index" }, name: "All Products" },
            { url: { name: "product.create" }, name: "New Product" },
            { url: { name: "product.review" }, name: "All Reviews" },
            { url: { name: "product.qa" }, name: "All QAs" },
          ],
        },
        {
          name: "Orders",
          icon: "receipt_long",
          url: { name: "orders.index" },
          children: [],
        },
        {
          name: "Takeaway Orders",
          icon: "sync_alt",
          url: { name: "orders.takeaway" },
          children: [],
        },

        {
          name: "Dinein Requests",
          icon: "list",
          url: { name: "dinein.index" },
          children: [],
        },
        /*{
            url: { name: "notification.index" },
            name: "Notifications",
            icon: "notifications",
            children: []
          },*/
      ],
    };
  },

  methods: {
    isActive(urlName) {
      if (this.$route.name !== null) return this.$route.name.includes(urlName);

      return false;
    },
  },

  computed: {
    userNavNames() {
      return this.userNavItems.map((item) => item.url.name);
    },

    isUserNav() {
      // check if array contains the value
      return this.userNavNames.indexOf(this.$route.name) > -1;
    },

    backgroundImage() {
      return ROOT_URL + "dashboard/img/sidebar-2.jpg";
    },

    logoutUrl() {
      return LOGOUT_URL;
    },
  },
};
</script>
