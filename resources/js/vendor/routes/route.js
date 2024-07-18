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
                    /* webpackChunkName: "./js/vendor/pages/[request]" */
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
                    /* webpackChunkName: "./js/vendor/pages/[request]" */
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
            // component: () => import(/* webpackChunkName: "./js/vendor/pages/edit-[request]" */ `@pages/${resource}/Create`),
            meta: {
                title: `Edit ${resource}`,
            },
        },
    ];
}

let routes = [{
    path: "/vendor/v1",
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
                            /* webpackChunkName: "./js/vendor/pages/my-profile" */
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
                            /* webpackChunkName: "./js/vendor/pages/change-password" */
                            "@pages/user/ChangePassword"
                        ),
                    meta: {
                        title: "Change Password",
                    },
                },
            ],
        },
        {
            path: "notifications",
            name: "notification.index",
            // component: require("@pages/notification/Index").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/vendor/pages/notification-Index" */
                    "@pages/notification/Index"
                ),
            meta: {
                title: "All Notifications",
            },
        },

        {
            path: "product",
            component: Parent,
            children: resourceUrl("product", "products"),
        },

        {
            path: "reviews",
            name: "product.review",
            // component: require("@pages/product/Review").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/vendor/pages/product-review-Index" */
                    "@pages/product/Review"
                ),
            meta: {
                title: "Not Verified Reviews",
            },
        },

        {
            path: "qas",
            name: "product.qa",
            // component: require("@pages/product/Qa").default,
            component: () =>
                import (
                    /* webpackChunkName: "./js/vendor/pages/product-qa-Index" */
                    "@pages/product/Qa"
                ),
            meta: {
                title: "All QAs",
            },
        },

        {
            path: "orders",
            name: "orders.index",
            component: () =>
                import ("@pages/orders/Index"),
            meta: {
                title: "All Orders",
            },
        },

        {
            path: "takeawayorders",
            name: "orders.takeaway",
            component: () =>
                import ("@pages/orders/Takeaway"),
            meta: {
                title: "All Takeaway Orders",
            },
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

        { path: "*", redirect: { name: "home" } },
    ],
}, ];

let router = new VueRouter({
    mode: "history",
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = "Vendor Dashboard - " + to.meta.title;
    next();
});

export default router;