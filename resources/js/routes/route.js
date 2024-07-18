import VueRouter from "vue-router";
import startCase from "lodash/startCase";

import Parent from "@pages/Parent";

let pluralise = require("pluralise");

function resourceUrl(resource, plural) {
    return [{
            path: "all",
            name: resource + ".index",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/[request]" */
                    `@pages/${resource}/Index`
                ),
            meta: {
                title: "All " + startCase(plural || pluralise(2, resource)),
            },
        },
        {
            path: "create",
            name: resource + ".create",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/[request]" */
                    `@pages/${resource}/Create`
                ),
            meta: {
                title: `Add a New ${resource}`,
            },
        },
        {
            path: ":id/edit",
            name: resource + ".edit",
            component: require(`@pages/${resource}/Create`).default,
            // component: () => import(/* webpackChunkName: "./js/admin/pages/edit-[request]" */ `@pages/${resource}/Create`),
            meta: {
                title: `Edit ${resource}`,
            },
        },
    ];
}

let routes = [{
    path: "/admin/v1",
    component: Parent,
    children: [{
            path: "/",
            name: "home",
            component: require("@pages/Index").default,
            meta: {
                title: "Home",
            },
        },
        {
            path: "user",
            component: Parent,
            children: [{
                    path: "my-profile",
                    name: "user.profile",
                    component: () =>
                        import (
                            /* webpackChunkName: "./js/admin/pages/my-profile" */
                            "@pages/user/Profile"
                        ),
                    meta: {
                        title: "My Profile",
                    },
                },
                {
                    path: "change-password",
                    name: "user.changePassword",
                    component: () =>
                        import (
                            /* webpackChunkName: "./js/admin/pages/change-password" */
                            "@pages/user/ChangePassword"
                        ),
                    meta: {
                        title: "Change Password",
                    },
                },
            ],
        },
        {
            path: "settings",
            name: "settings",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/setting" */
                    "@pages/Setting"
                ),
            meta: {
                title: "Settings",
            },
        },

        {
            path: "tor",
            name: "tor",
            component: () =>
                import ("@pages/Tor"),
            meta: {
                title: "TOR",
            },
        },

        {
            path: "service",
            component: Parent,
            children: resourceUrl("service", "services"),
        },

        {
            path: "testimonial",
            component: Parent,
            children: resourceUrl("testimonial"),
        },
        {
            path: "news",
            component: Parent,
            children: resourceUrl("news", "news"),
        },
        {
            path: "social",
            component: Parent,
            children: resourceUrl("social"),
        },
        {
            path: "notifications",
            name: "notification.index",
            // component: require("@pages/notification/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/notification-Index" */
                    "@pages/notification/Index"
                ),
            meta: {
                title: "All Notifications",
            },
        },
        {
            path: "product-category",
            component: Parent,
            children: resourceUrl("product-category", "product-categories"),
        },
        {
            path: "product-category/list",
            name: "product-category.list",
            component: () =>
                import ("@pages/product-category/List"),
            meta: {
                title: "Product Category List",
            },
        },
        {
            path: "unit",
            component: Parent,
            children: resourceUrl("unit", "units"),
        },
        {
            path: "vendor",
            component: Parent,
            children: resourceUrl("vendor", "vendors"),
        },
        {
            path: "users",
            name: "users",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/user-Index" */
                    "@pages/user/Index"
                ),
            meta: {
                title: "Users",
            },
        },
        {
            path: "drivers",
            name: "driver.index",
            // component: require("@pages/driver/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/driver-Index" */
                    "@pages/driver/Index"
                ),
            meta: {
                title: "All Drivers",
            },
        },
        {
            path: "delivery-driver",
            name: "delivery-driver.index",
            component: () =>
                import ("@pages/delivery-driver/Index"),
            meta: {
                title: "All Delivery Drivers",
            },
        },
        {
            path: "products",
            component: Parent,
            children: resourceUrl("product", "products"),
        },
        {
            path: "premium-place",
            component: Parent,
            children: resourceUrl("premium-place", "premium-places"),
        },
        {
            path: "riding-fare",
            component: Parent,
            children: resourceUrl("riding-fare", "riding-fares"),
        },
        {
            path: "faq",
            component: Parent,
            children: resourceUrl("faq", "faqs"),
        },
        {
            path: "rental-package",
            component: Parent,
            children: resourceUrl("rental-package", "rental-packages"),
        },
        {
            path: "rental-trip",
            name: "rental-trip.index",
            // component: require("@pages/rental-trip/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/rental-trip-Index" */
                    "@pages/rental-trip/Index"
                ),
            meta: {
                title: "All Rental Trips",
            },
        },
        {
            path: "outstation-trip",
            name: "outstation-trip.index",
            // component: require("@pages/outstation-trip/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/outstation-trip-Index" */
                    "@pages/outstation-trip/Index"
                ),
            meta: {
                title: "All Outstation Trips",
            },
        },
        {
            path: "launchpad-category",
            component: Parent,
            children: resourceUrl("launchpad-category", "launchpad-categories"),
        },
        {
            path: "launchpad",
            component: Parent,
            children: resourceUrl("launchpad", "launchpads"),
        },
        {
            path: "slider",
            component: Parent,
            children: resourceUrl("slider", "sliders"),
        },
        {
            path: "website-slider",
            component: Parent,
            children: resourceUrl("website-slider", "website-sliders"),
        },
        {
            path: "product-option-category",
            component: Parent,
            children: resourceUrl(
                "product-option-category",
                "product-option-categories"
            ),
        },
        {
            path: "product-option",
            name: "product-option.index",
            component: () =>
                import ("@pages/product-option/Index"),
            meta: {
                title: "Product Option Sort",
            },
        },
        {
            path: "vendor-option-category",
            component: Parent,
            children: resourceUrl(
                "vendor-option-category",
                "vendor-option-categories"
            ),
        },
        {
            path: "order",
            name: "order.index",
            // component: require("@pages/order/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/order-Index" */
                    "@pages/order/Index"
                ),
            meta: {
                title: "All Orders",
            },
        },
        {
            path: "order-feedback",
            name: "order.feedback",
            component: () =>
                import ("@pages/order/Feedback"),
            meta: {
                title: "All Order Feedbacks",
            },
        },
        {
            path: "trip",
            name: "trip.index",
            // component: require("@pages/trip/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/trip-Index" */
                    "@pages/trip/Index"
                ),
            meta: {
                title: "All Trips",
            },
        },
        {
            path: "delivery",
            name: "delivery.index",
            // component: require("@pages/delivery/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/delivery-Index" */
                    "@pages/delivery/Index"
                ),
            meta: {
                title: "All Deliveries",
            },
        },
        {
            path: "super-user",
            component: Parent,
            children: resourceUrl("super-user", "super-users"),
        },
        {
            path: "coupon",
            component: Parent,
            children: resourceUrl("coupon", "coupons"),
        },
        {
            path: "donations",
            name: "donations",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/donation-Index" */
                    "@pages/donation/Index"
                ),
            meta: {
                title: "Donations",
            },
        },
        {
            path: "global-notification",
            component: Parent,
            children: resourceUrl("global-notification", "global-notifications"),
        },
        {
            path: "road-block",
            component: Parent,
            children: resourceUrl("road-block", "road-block"),
        },
        {
            path: "payments",
            name: "payments",
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/payment-Index" */
                    "@pages/payment/Index"
                ),
            meta: {
                title: "Driver Payments",
            },
        },
        {
            path: "gogo-ad",
            component: Parent,
            children: resourceUrl("ad", "ads"),
        },
        {
            path: "discount",
            component: Parent,
            children: resourceUrl("discount"),
        },
        {
            path: "send",
            component: Parent,
            children: resourceUrl("send"),
        },
        {
            path: "pool",
            component: Parent,
            children: resourceUrl("pool"),
        },
        {
            path: "items",
            component: Parent,
            children: resourceUrl("items"),
        },
        {
            path: "paymentlog",
            name: "paymentlog.index",
            // component: require("@pages/paymentlog/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/admin/pages/paymentlog-Index" */
                    "@pages/paymentlog/Index"
                ),
            meta: {
                title: "All Payment Logs",
            },
        },
        {
            path: "khaltilog",
            name: "khaltilog.index",
            component: () =>
                import ("@pages/khaltilog/Index"),
            meta: {
                title: "Khalti Log",
            },
        },

        {
            path: "esewalog",
            name: "esewalog.index",
            component: () =>
                import ("@pages/esewalog/Index"),
            meta: {
                title: "Esewa Log",
            },
        },

        {
            path: "refund",
            name: "refund.index",
            component: () =>
                import ("@pages/refund/Index"),
            meta: {
                title: "Refundable Order List",
            },
        },

        {
            path: "vendorsettle",
            name: "settlement.vendor",
            component: () =>
                import ("@pages/settlement/Vendor"),
            meta: {
                title: "Vendor Settlement",
            },
        },

        {
            path: "conf",
            name: "conf.Create",
            component: () =>
                import ("@pages/conf/Create"),
            meta: {
                title: "Global Configuration",
            },
        },
        {
            path: "partner",
            component: Parent,
            children: resourceUrl("partner"),
        },
        // Branches
        {
            path: "partner/:parentId/branches",
            name: "partner.branches",
            component: () =>
                import ("@pages/partner/Branch"),
            meta: {
                title: "Add Branches",
            },
        },
        {
            path: "partner/:parentId",
            name: "partner.listbranch",
            component: () =>
                import ("@pages/partner/BranchList"),
            meta: {
                title: "List Branches",
            },
        },

        {
            path: "reset",
            name: "password.index",
            component: () =>
                import ("@pages/password/Index"),
            meta: {
                title: "Reset Password",
            },
        },

        {
            path: "package",
            component: Parent,
            children: resourceUrl("package"),
        },
        {
            path: "inhouse-pilot",
            name: "inhouse-pilot",
            component: () =>
                import ("@pages/payment/Inhouse"),
            meta: {
                title: "In-House Pilots",
            },
        },
        {
            path: "vendor-review",
            name: "vendor-review.index",
            component: () =>
                import ("@pages/vendor-review/Index"),
            meta: {
                title: "Vendor Review List",
            },
        },
        {
            path: "layout-manager",
            component: Parent,
            children: resourceUrl("layout-manager"),
        },
        {
            path: "delivery-junction",
            component: Parent,
            children: resourceUrl("delivery-junction"),
        },
        {
            path: "product",
            name: "product.compare",
            component: () =>
                import ("@pages/product/Compare"),
            meta: {
                title: "Product Comparison",
            },
        },
        {
            path: "elite",
            name: "elite.list",
            component: () =>
                import ("@pages/elite/List"),
            meta: {
                title: "gogoElite Request List",
            },
        },
        {
            path: "vendor-option",
            name: "vendor-option.index",
            component: () =>
                import ("@pages/vendor-option/Index"),
            meta: {
                title: "Vendor Option Sort",
            },
        },
        {
            path: "vendor-discount",
            name: "vendor-discount.index",
            component: () =>
                import ("@pages/vendor-discount/Index"),
            meta: {
                title: "Vendor Discount List",
            },
        },
        {
            path: "campaign",
            component: Parent,
            children: resourceUrl("campaign", "campaigns"),
        },
        {
            path: "voucher",
            component: Parent,
            children: resourceUrl("voucher", "vouchers"),
        },
        {
            path: "additional-service",
            component: Parent,
            children: resourceUrl("additional-service", "additional-services"),
        },
        {
            path: "academy-slider",
            component: Parent,
            children: resourceUrl("academy-slider", "academy-sliders"),
        },
        {
            path: "academy-content",
            component: Parent,
            children: resourceUrl("academy-content", "academy-contents"),
        },
        {
            path: "dinein",
            name: "dinein.index",
            component: () =>
                import ("@pages/dinein/Index"),
            meta: {
                title: "All Dine in Requests",
            },
        },
        //Please use this section for report stuffs
        {
            path: "reports",
            name: "reports.index",
            component: () =>
                import ("@pages/reports/Index"),
            meta: {
                title: "Reports - Data Visualization",
            },
        },
        {
            path: "app-users-report/:filter?",
            name: "app-users-report.index",
            component: () =>
                import ("@pages/report-users/Index"),
            meta: {
                title: "App Users Reports - Data Visualization",
            },
        },
        {
            path: "vendors-report/:filter?",
            name: "vendors-report.index",
            component: () =>
                import ("@pages/report-vendors/Index"),
            meta: {
                title: "Vendor Reports - Data Visualization",
            },
        },
        {
            path: "drivers-report/:filter?",
            name: "drivers-report.index",
            component: () =>
                import ("@pages/report-riders/Index"),
            meta: {
                title: "Drivers Reports - Data Visualization",
            },
        },
        {
            path: "orders-report/:filter?",
            name: "orders-report.index",
            component: () =>
                import ("@pages/report-orders/Index"),
            meta: {
                title: "Orders Reports - Data Visualization",
            },
        },
        {
            path: "trips-report/:filter?",
            name: "trips-report.index",
            component: () =>
                import ("@pages/report-trips/Index"),
            meta: {
                title: "Trips Reports - Data Visualization",
            },
        },
        // Settlement
        {
            path: "vendor-advance-settlement/:filter?",
            name: "settlement.advancesettlement",
            component: () =>
                import ("@pages/advance-settlement/Vendor"),
            meta: {
                title: "Vendor Advance Settlement - List",
            },
        },
        // Deals
        {
            path: "deal",
            component: Parent,
            children: resourceUrl("deal", "deals"),
        },
        // Deal Products
        {
            path: "deal-products/:dealId",
            name: "deal.products",
            component: () =>
                import ("@pages/deal/DealProduct"),
            meta: {
                title: "Deal Products - List and Add",
            },
        },

        // Settled List
        {
            path: "vendor-settled",
            name: "vendor.settled",
            component: () =>
                import ("@pages/settlement/SettledList"),
            meta: {
                title: "Vendor Settled - List",
            },
        },

        // User Transactions
        {
            path: "user-transaction/:userId?",
            name: "user.transaction",
            component: () =>
                import ("@pages/report-users/UserTransaction"),
            meta: {
                title: "User Transactions - List",
            },
        },

        // Top User Transactions
        {
            path: "top-user-transaction",
            name: "user.toptransaction",
            component: () =>
                import ("@pages/report-users/TopUserTransactions"),
            meta: {
                title: "Top User Transactions - List",
            },
        },

        // Utility Coupon Code
        {
            path: "utility-coupon",
            component: Parent,
            children: resourceUrl("utility-coupon", "utility-coupons"),
        },
        // Utility Voucher Code
        {
            path: "utility-voucher",
            component: Parent,
            children: resourceUrl("utility-voucher", "utility-vouchers"),
        },

        // Order Offer Config
        {
            path: "order-offer",
            name: "order-offer.Create",
            component: () =>
                import ("@pages/order-offer/Create"),
            meta: {
                title: "Order Offer Configuration",
            },
        },
        // Ride Offer Config
        {
            path: "ride-offer",
            name: "ride-offer.Create",
            component: () =>
                import ("@pages/ride-offer/Create"),
            meta: {
                title: "Ride Offer Configuration",
            },
        },
        {
            path: "referrals-report",
            name: "referrals-report.index",
            component: () =>
                import ("@pages/report-referrals/Index"),
            meta: {
                title: "Referrals Report - Data Visualization",
            },
        },
        {
            path: "order-return",
            name: "order.return",
            component: () =>
                import ("@pages/order/Return"),
            meta: {
                title: "All Order Return",
            },
        },
        {
            path: "order-detail/:id",
            name: "order.detail",
            component: () =>
                import ("@pages/order/OrderDetail"),
            meta: {
                title: "Order Detail",
            },
        },
        // Prescription Requests
        {
            path: "prescription-request",
            name: "prescription-request.index",
            component: () =>
                import ("@pages/prescription/Index"),
            meta: {
                title: "All Prescription in Requests",
            },
        },
        {
            path: "wallet-log",
            name: "wallet-log",
            component: () =>
                import ("@pages/payment/WalletLog"),
            meta: {
                title: "Wallet Advance Logs",
            },
        },
        {
            path: "wallet-payment-log",
            name: "wallet-payment-log",
            component: () =>
                import ("@pages/payment/WalletPaymentLog"),
            meta: {
                title: "Wallet Payment Logs",
            },
        },
        {
            path: "add-to-cart",
            name: "add-to-cart",
            component: () =>
                import ("@pages/cart/Index"),
            meta: {
                title: "Add To Cart",
            },
        },

        {
            path: "hospital",
            component: Parent,
            children: resourceUrl("hospital"),
        },

        {
            path: "*",
            redirect: {
                name: "home",
            },
        },
    ],
}, ];

let router = new VueRouter({
    mode: "history",
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title + " | Dashboard";
    next();
});

export default router;