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
      <div id="sideNavbar">
        <ul
          class="panel-group nav real-left-nav-menus"
          v-if="authUser.type === 'superadmin' || authUser.type === 'admin'"
          id="accordion"
        >
          <li
            :class="{
              active: isActive(leftNavItem.url.name),
              'panel panel-default has-drop-down':
                leftNavItem.children.length > 0,
              'collapse-all': leftNavItem.children.length <= 0,
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
              data-parent="#accordion"
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
        
      <ul class="nav real-left-nav-menus" v-if="authUser.type === 'unit-head'">
        <li
          :class="{
            active: isActive(leftNavItem.url.name),
            'has-drop-down': leftNavItem.children.length > 0,
          }"
          v-for="(leftNavItem, key) in hodNavItems"
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

      <ul class="nav real-left-nav-menus" v-if="authUser.type === 'manager'">
        <li
          :class="{
            active: isActive(leftNavItem.url.name),
            'has-drop-down': leftNavItem.children.length > 0,
          }"
          v-for="(leftNavItem, key) in managerNavItems"
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

      <ul class="nav real-left-nav-menus" v-if="authUser.type === 'officer'">
        <li
          :class="{
            active: isActive(leftNavItem.url.name),
            'has-drop-down': leftNavItem.children.length > 0,
          }"
          v-for="(leftNavItem, key) in officerNavItems"
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

      <ul class="nav real-left-nav-menus" v-if="authUser.type === 'support'">
        <li
          :class="{
            active: isActive(leftNavItem.url.name),
            'has-drop-down': leftNavItem.children.length > 0,
          }"
          v-for="(leftNavItem, key) in supportNavItems"
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
          name: "Registered",
          icon: "people",
          url: { name: "super-user" },
          children: [
            { url: { name: "users" }, name: "App Users" },
            { url: { name: "driver.index" }, name: "Riders" },
            { url: { name: "delivery-driver.index" }, name: "Delivery Riders" },
            { url: { name: "super-user.index" }, name: "System Users" },
            { url: { name: "vendor.index" }, name: "Vendors" },
          ],
        },
        {
          name: "Rides",
          icon: "drive_eta",
          url: { name: "ride" },
          children: [
            {
              url: { name: "premium-place.index" },
              name: "Premium & Outstation",
            },
            { url: { name: "rental-package.index" }, name: "Rental Packages" },
            { url: { name: "rental-trip.index" }, name: "Rental Trips" },
            { url: { name: "riding-fare.index" }, name: "Riding Fares" },
            {
              url: { name: "outstation-trip.index" },
              name: "Outstation Trips",
            },
            { url: { name: "trip.index" }, name: "On-Going Trips" },
          ],
        },
        {
          name: "On-Demand",
          icon: "hdr_strong",
          url: { name: "on-demand" },
          children: [
            { url: { name: "add-to-cart" }, name: "Add to cart" },
            { url: { name: "coupon.index" }, name: "Coupon Codes" },
            { url: { name: "deal.index" }, name: "Deals" },
            { url: { name: "delivery.index" }, name: "Deliveries" },
            {
              url: { name: "delivery-junction.index" },
              name: "Delivery Junctions",
            },
            {
              name: "Dine-In Requests",
              icon: "list",
              url: { name: "dinein.index" },
              children: [],
            },
            // {
            //   url: { name: "hospital.index" },
            //   name: "Hospital",
            // },
            { url: { name: "order.index" }, name: "Orders" },
            { url: { name: "order.feedback" }, name: "Order Feedbacks" },
            { url: { name: "order.return" }, name: "Order Returns" },
            // { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
            { url: { name: "product.index" }, name: "Products" },
            {
              url: { name: "product-category.index" },
              name: "Product Categories Sort",
            },
            {
              url: { name: "product-category.list" },
              name: "Product Categories List",
            },
            {
              url: { name: "product-option-category.index" },
              name: "Product Opt Categories",
            },
            {
              url: { name: "product-option.index" },
              name: "Product Opt Sort",
            },
            { url: { name: "slider.index" }, name: "Service Sliders" },
            {
              url: { name: "vendor-option-category.index" },
              name: "Vendor Opt Categories",
            },
            {
              url: { name: "vendor-option.index" },
              name: "Vendor Opt Sort",
            },
            // { url: { name: "unit.index" }, name: "Product Units" },
            { url: { name: "vendor-review.index" }, name: "Vendor Reviews" },
          ],
        },
        {
          name: "gogoHealth",
          icon: "health_and_safety",
          url: { name: "gogo-health" },
          children: [
            {
              url: { name: "hospital.index" },
              name: "Hospital",
            },
            { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
          ],
        },
        {
          name: "gogoSend-Pool",
          icon: "present_to_all",
          url: { name: "gogo-send-pool" },
          children: [
            { url: { name: "discount.index" }, name: "Discount" },
            { url: { name: "pool.index" }, name: "gogoPool" },
            { url: { name: "send.index" }, name: "gogoSend" },
            { url: { name: "items.index" }, name: "Send Items" },
          ],
        },
        {
          name: "Financials",
          icon: "monetization_on",
          url: { name: "settlement" },
          children: [
            { url: { name: "payments" }, name: "Driver Payments" },
            {
              url: { name: "inhouse-pilot" },
              name: "In-House Pilots",
            },
            { url: { name: "paymentlog.index" }, name: "Payment Logs" },
            { url: { name: "refund.index" }, name: "Refund" },
            { url: { name: "settlement.vendor" }, name: "Vendor" },
            { url: { name: "wallet-log" }, name: "Wallet Advance Logs" },
            { url: { name: "wallet-payment-log" }, name: "Wallet Payment Logs" },
            // { url: { name: "pool.index" }, name: "gogoPool" },
            // { url: { name: "discount.index" }, name: "Discount" },
            // { url: { name: "items.index" }, name: "Send Items" },
          ],
        },

        {
          name: "Settings",
          icon: "settings",
          url: { name: "settings" },
          children: [
            { url: { name: "campaign.index" }, name: "Campaigns" },
            { url: { name: "donations" }, name: "Donations" },
            { url: { name: "faq.index" }, name: "FAQs" },
            {
              url: { name: "conf.Create" },
              name: "Global Config",
            },
            {
              url: { name: "global-notification.index" },
              name: "Global Notification",
            },
            { url: { name: "ad.index" }, name: "gogoAds" },
            {
              url: { name: "elite.list" },
              name: "gogoElite Request List",
            },
            { url: { name: "launchpad.index" }, name: "Launchpads" },
            {
              url: { name: "launchpad-category.index" },
              name: "LaunchPad Category",
            },
            {
              url: { name: "layout-manager.index" },
              name: "Layout Manager",
            },
            {
              url: { name: "order-offer.Create" },
              name: "Order Offer Config",
            },
            {
              url: { name: "ride-offer.Create" },
              name: "Ride Offer Config",
            },
            { url: { name: "partner.index" }, name: "Our Partners" },
            { url: { name: "password.index" }, name: "Reset Password" },
            {
              url: { name: "road-block.index" },
              name: "Road Block Notification",
            },
            { url: { name: "package.index" }, name: "Riders Subscription" },
            { url: { name: "service.index" }, name: "Services" },

            { url: { name: "tor" }, name: "TOR" },
            {
              url: { name: "vendor-discount.index" },
              name: "Vendor Discounts",
            },
            { url: { name: "voucher.index" }, name: "Vouchers" },
            { url: { name: "settings" }, name: "Website" },
            { url: { name: "website-slider.index" }, name: "Website Sliders" },
          ],
        },
        {
          name: "Utility",
          icon: "construction",
          url: { name: "utility" },
          children: [
            {
              url: { name: "additional-service.index" },
              name: "Services",
            },
            {
              url: { name: "utility-coupon.index" },
              name: "Utility Promo Code",
            },
            {
              url: { name: "utility-voucher.index" },
              name: "Utility Voucher",
            },
          ],
        },
        {
          name: "gogoAcademy",
          icon: "school",
          url: { name: "academy" },
          children: [
            {
              url: { name: "academy-slider.index" },
              name: "Sliders",
            },
            {
              url: { name: "academy-content.index" },
              name: "Contents",
            },
          ],
        },
        {
          name: "Reports",
          icon: "description",
          url: { name: "reports" },
          children: [
            {
              url: { name: "reports.index" },
              name: "Dashboard",
            },
            {
              url: { name: "app-users-report.index" },
              name: "App Users",
            },
            {
              url: { name: "vendors-report.index" },
              name: "Vendors",
            },
            {
              url: { name: "drivers-report.index" },
              name: "Drivers",
            },
            {
              url: { name: "orders-report.index" },
              name: "Orders",
            },
            {
              url: { name: "trips-report.index" },
              name: "Trips",
            },
            {
              url: { name: "referrals-report.index" },
              name: "Referrals",
            },
          ],
        },
      ],
      hodNavItems: [
        {
          name: "Dashboard",
          icon: "account_balance",
          url: { name: "home" },
          children: [],
        },

        {
          name: "Registered",
          icon: "people",
          url: { name: "super-user" },
          children: [
            { url: { name: "users" }, name: "App Users" },
            { url: { name: "driver.index" }, name: "Riders" },
            { url: { name: "delivery-driver.index" }, name: "Delivery Riders" },
            { url: { name: "vendor.index" }, name: "Vendors" },
          ],
        },
        {
          name: "Rides",
          icon: "drive_eta",
          url: { name: "ride" },
          children: [
            {
              url: { name: "premium-place.index" },
              name: "Premium & Outstation",
            },
            { url: { name: "rental-package.index" }, name: "Rental Packages" },
            { url: { name: "rental-trip.index" }, name: "Rental Trips" },
            { url: { name: "riding-fare.index" }, name: "Riding Fares" },
            {
              url: { name: "outstation-trip.index" },
              name: "Outstation Trips",
            },
            { url: { name: "trip.index" }, name: "On-Going Trips" },
          ],
        },
        {
          name: "On-Demand",
          icon: "hdr_strong",
          url: { name: "on-demand" },
          children: [
            { url: { name: "add-to-cart" }, name: "Add to cart" },
            { url: { name: "coupon.index" }, name: "Coupon Codes" },
            { url: { name: "deal.index" }, name: "Deals" },
            { url: { name: "delivery.index" }, name: "Deliveries" },
            {
              url: { name: "delivery-junction.index" },
              name: "Delivery Junctions",
            },
            {
              name: "Dine-In Requests",
              icon: "list",
              url: { name: "dinein.index" },
              children: [],
            },
            // {
            //   url: { name: "hospital.index" },
            //   name: "Hospital",
            // },
            { url: { name: "order.index" }, name: "Orders" },
            { url: { name: "order.feedback" }, name: "Order Feedbacks" },
            { url: { name: "order.return" }, name: "Order Returns" },
            // { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
            { url: { name: "product.index" }, name: "Products" },
            {
              url: { name: "product-category.index" },
              name: "Product Categories Sort",
            },
            {
              url: { name: "product-category.list" },
              name: "Product Categories List",
            },
            {
              url: { name: "product-option-category.index" },
              name: "Product Opt Categories",
            },
            {
              url: { name: "product-option.index" },
              name: "Product Opt Sort",
            },
            { url: { name: "slider.index" }, name: "Service Sliders" },
            {
              url: { name: "vendor-option-category.index" },
              name: "Vendor Opt Categories",
            },
            {
              url: { name: "vendor-option.index" },
              name: "Vendor Opt Sort",
            },
            // { url: { name: "unit.index" }, name: "Product Units" },
            { url: { name: "vendor-review.index" }, name: "Vendor Reviews" },
          ],
        },
        {
          name: "gogoHealth",
          icon: "health_and_safety",
          url: { name: "gogo-health" },
          children: [
            {
              url: { name: "hospital.index" },
              name: "Hospital",
            },
            { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
          ],
        },
        {
          name: "gogoSend-Pool",
          icon: "present_to_all",
          url: { name: "gogo-send-pool" },
          children: [
            { url: { name: "discount.index" }, name: "Discount" },
            { url: { name: "pool.index" }, name: "gogoPool" },
            { url: { name: "send.index" }, name: "gogoSend" },
            { url: { name: "items.index" }, name: "Send Items" },
          ],
        },
        {
          name: "Financials",
          icon: "monetization_on",
          url: { name: "settlement" },
          children: [
            { url: { name: "payments" }, name: "Driver Payments" },
            {
              url: { name: "inhouse-pilot" },
              name: "In-House Pilots",
            },
            { url: { name: "paymentlog.index" }, name: "Payment Logs" },
            { url: { name: "refund.index" }, name: "Refund" },
            { url: { name: "settlement.vendor" }, name: "Vendor" },
            { url: { name: "wallet-log" }, name: "Wallet Advance Logs" },
            { url: { name: "wallet-payment-log" }, name: "Wallet Payment Logs" },
            // { url: { name: "pool.index" }, name: "gogoPool" },
            // { url: { name: "discount.index" }, name: "Discount" },
            // { url: { name: "items.index" }, name: "Send Items" },
          ],
        },

        {
          name: "Settings",
          icon: "settings",
          url: { name: "settings" },
          children: [
            { url: { name: "campaign.index" }, name: "Campaigns" },
            { url: { name: "donations" }, name: "Donations" },
            { url: { name: "faq.index" }, name: "FAQs" },
            {
              url: { name: "conf.Create" },
              name: "Global Config",
            },
            {
              url: { name: "global-notification.index" },
              name: "Global Notification",
            },
            { url: { name: "ad.index" }, name: "gogoAds" },
            {
              url: { name: "elite.list" },
              name: "gogoElite Request List",
            },
            { url: { name: "launchpad.index" }, name: "Launchpads" },
            {
              url: { name: "launchpad-category.index" },
              name: "LaunchPad Category",
            },
            {
              url: { name: "layout-manager.index" },
              name: "Layout Manager",
            },
            {
              url: { name: "order-offer.Create" },
              name: "Order Offer Config",
            },
            { url: { name: "partner.index" }, name: "Our Partners" },
            { url: { name: "password.index" }, name: "Reset Password" },
            {
              url: { name: "road-block.index" },
              name: "Road Block Notification",
            },
            { url: { name: "package.index" }, name: "Riders Subscription" },
            { url: { name: "service.index" }, name: "Services" },

            { url: { name: "tor" }, name: "TOR" },
            {
              url: { name: "vendor-discount.index" },
              name: "Vendor Discounts",
            },
            { url: { name: "voucher.index" }, name: "Vouchers" },
            { url: { name: "settings" }, name: "Website" },
            { url: { name: "website-slider.index" }, name: "Website Sliders" },
          ],
        },
        {
          name: "Utility",
          icon: "construction",
          url: { name: "utility" },
          children: [
            {
              url: { name: "additional-service.index" },
              name: "Services",
            },
            {
              url: { name: "utility-coupon.index" },
              name: "Utility Promo Code",
            },
            {
              url: { name: "utility-voucher.index" },
              name: "Utility Voucher",
            },
          ],
        },
        {
          name: "gogoAcademy",
          icon: "school",
          url: { name: "academy" },
          children: [
            {
              url: { name: "academy-slider.index" },
              name: "Sliders",
            },
            {
              url: { name: "academy-content.index" },
              name: "Contents",
            },
          ],
        }
      ],
      managerNavItems: [
        {
          name: "Dashboard",
          icon: "account_balance",
          url: { name: "home" },
          children: [],
        },
        {
          name: "Rides",
          icon: "drive_eta",
          url: { name: "ride" },
          children: [
            {
              url: { name: "premium-place.index" },
              name: "Premium & Outstation",
            },
            { url: { name: "rental-package.index" }, name: "Rental Packages" },
            { url: { name: "rental-trip.index" }, name: "Rental Trips" },
            { url: { name: "riding-fare.index" }, name: "Riding Fares" },
            {
              url: { name: "outstation-trip.index" },
              name: "Outstation Trips",
            },
            { url: { name: "trip.index" }, name: "On-Going Trips" },
          ],
        },
        {
          name: "On-Demand",
          icon: "hdr_strong",
          url: { name: "on-demand" },
          children: [
            { url: { name: "add-to-cart" }, name: "Add to Cart" },
            { url: { name: "coupon.index" }, name: "Coupon Codes" },
            { url: { name: "deal.index" }, name: "Deals" },
            { url: { name: "delivery.index" }, name: "Deliveries" },
            {
              url: { name: "delivery-junction.index" },
              name: "Delivery Junctions",
            },
            {
              name: "Dine-In Requests",
              icon: "list",
              url: { name: "dinein.index" },
              children: [],
            },
            { url: { name: "order.index" }, name: "Orders" },
            { url: { name: "order.feedback" }, name: "Order Feedbacks" },
            { url: { name: "order.return" }, name: "Order Returns" },
            { url: { name: "product.index" }, name: "Products" },
            {
              url: { name: "product-category.index" },
              name: "Product Categories Sort",
            },
            {
              url: { name: "product-category.list" },
              name: "Product Categories List",
            },
            {
              url: { name: "product-option-category.index" },
              name: "Product Opt Categories",
            },
            {
              url: { name: "product-option.index" },
              name: "Product Opt Sort",
            },
            { url: { name: "slider.index" }, name: "Service Sliders" },
            {
              url: { name: "vendor-option-category.index" },
              name: "Vendor Opt Categories",
            },
            {
              url: { name: "vendor-option.index" },
              name: "Vendor Opt Sort",
            },
            // { url: { name: "unit.index" }, name: "Product Units" },
            { url: { name: "vendor-review.index" }, name: "Vendor Reviews" },
          ],
        },
        {
          name: "gogoHealth",
          icon: "health_and_safety",
          url: { name: "gogo-health" },
          children: [
            {
              url: { name: "hospital.index" },
              name: "Hospital",
            },
            { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
          ],
        },
        {
          name: "gogoAcademy",
          icon: "school",
          url: { name: "academy" },
          children: [
            {
              url: { name: "academy-slider.index" },
              name: "Sliders",
            },
            {
              url: { name: "academy-content.index" },
              name: "Contents",
            },
          ],
        },
      ],
      officerNavItems: [
        {
          name: "Dashboard",
          icon: "account_balance",
          url: { name: "home" },
          children: [],
        },
        {
          name: "Settings",
          icon: "settings",
          url: { name: "settings" },
          children: [],
        },
        {
          name: "LaunchPad",
          icon: "library_music",
          url: { name: "launchpad" },
          children: [
            { url: { name: "launchpad-category.index" }, name: "Category" },
            { url: { name: "launchpad.index" }, name: "All Launchpads" },
          ],
        },
        {
          name: "Users",
          icon: "people",
          url: { name: "users" },
          children: [],
        },
        {
          name: "On Demand",
          icon: "hdr_strong",
          url: { name: "service" },
          children: [
            { url: { name: "vendor.index" }, name: "Vendors" },

            {
              url: { name: "product-category.index" },
              name: "Product Categories",
            },
            {
              url: { name: "product-option-category.index" },
              name: "Option Categories",
            },
            { url: { name: "product.index" }, name: "Products" },
            { url: { name: "unit.index" }, name: "Product Units" },
            { url: { name: "slider.index" }, name: "Service Sliders" },
            { url: { name: "order.index" }, name: "Orders" },
          ],
        },
        {
          name: "gogoHealth",
          icon: "health_and_safety",
          url: { name: "gogo-health" },
          children: [
            {
              url: { name: "hospital.index" },
              name: "Hospital",
            },
            { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
          ],
        },
        {
          name: "Ride",
          icon: "drive_eta",
          url: { name: "ride" },
          children: [
            { url: { name: "driver.index" }, name: "Drivers" },
            { url: { name: "rental-trip.index" }, name: "Rental Trips" },
            { url: { name: "trip.index" }, name: "onGoing Trips" },
            { url: { name: "delivery.index" }, name: "onGoing Deliveries" },
          ],
        },
      ],
      supportNavItems: [
        {
          name: "gogoHealth",
          icon: "health_and_safety",
          url: { name: "gogo-health" },
          children: [
            { url: { name: "prescription-request.index" }, name: "Prescription Requests" },
          ],
        },
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
