(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      order: {
        items: [],
        delivery: {
          driver: {}
        },
        user: {},
        vendor: {},
        additionalDetail: []
      },
      assocOrders: {},
      orderFeedbacks: {
        admin: {},
        user: {}
      }
    };
  },
  methods: {
    formatDate: function formatDate(date) {
      if (date != "-") return moment(date).format("LLLL");else return "Not Available";
    },
    getData: function getData() {
      var _this = this;

      axios.get('/admin/order-detail/' + this.$route.params.id).then(function (response) {
        _this.order = response.data.data;
        _this.assocOrders = response.data.assocOrders;
        _this.orderFeedbacks = response.data.data.orderFeedback;
      });
    },
    printOrder: function printOrder() {
      alert('I am Print');
    }
  },
  mounted: function mounted() {
    if (this.$route.params.hasOwnProperty("id")) {
      this.getData();
    }

    if (this.order.delivery == null) {
      this.order.delivery = {
        driver: {}
      };
    }
  },
  created: function created() {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.medium-text{\n    font-size: 13px;\n    font-weight: 400;\n}\n.blue-text{\n    font-size: 16px;\n}\n.CANCELLED{\n    background: red;\n}\n.PENDING{\n    background: orange;\n}\n.title {\nfont-size: 14px;\nfont-weight:bold;\n}\n.komen {\n    font-size:14px;\n}\n.geser {\n    margin-left:55px;\n    margin-top:5px;\n}\n\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./OrderDetail.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { attrs: { id: "print" } },
    [
      _c(
        "app-card",
        {
          attrs: {
            title: "Order <b>Detail</b> - STATUS: " + _vm.order.status,
            "body-padding": "0"
          }
        },
        [
          _c("div", { staticClass: "row", staticStyle: { padding: "10px" } }, [
            _c("div", { staticClass: "col-md-6 text-left" }, [
              _c("h4", { staticClass: "h4-responsive" }, [
                _c("small", [_vm._v("Order No.")]),
                _c("br"),
                _c("strong", [
                  _c("span", { staticClass: "blue-text" }, [
                    _vm._v("#" + _vm._s(_vm.order.orderNo))
                  ])
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-6 text-right" }, [
              _c("h4", { staticClass: "h4-responsive" }, [
                _c("small", [_vm._v("Order Ref.")]),
                _c("br"),
                _c("strong", [
                  _c("span", { staticClass: "blue-text" }, [
                    _vm._v("#" + _vm._s(_vm.order.refNumber))
                  ])
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "row", staticStyle: { padding: "10px" } }, [
            _c("div", { staticClass: "col-md-2 text-left medium-text" }, [
              _c("p", [_c("small", [_vm._v("From:")])]),
              _vm._v(" "),
              _c("p", [
                _c("strong", [
                  _vm._v(
                    _vm._s(
                      _vm.order.user.firstName + " " + _vm.order.user.lastName
                    )
                  )
                ])
              ]),
              _vm._v(" "),
              _c("p", [_vm._v("Email: " + _vm._s(_vm.order.user.email))]),
              _vm._v(" "),
              _c("p", [_vm._v("Phone: " + _vm._s(_vm.order.user.phone))]),
              _vm._v(" "),
              _c("p", [
                _vm._v(
                  "Order date: " + _vm._s(_vm.formatDate(_vm.order.ordered_on))
                )
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v(
                  "Delivery date: " +
                    _vm._s(_vm.formatDate(_vm.order.delivery_date))
                )
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v("Payment Mode: " + _vm._s(_vm.order.payment_mode))
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-2 text-left medium-text" }, [
              _c("p", [_c("small", [_vm._v("Vendor:")])]),
              _vm._v(" "),
              _c("p", [
                _c("strong", [_vm._v(_vm._s(_vm.order.vendor.businessName))])
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v(
                  "Name: " +
                    _vm._s(
                      _vm.order.vendor.firstName +
                        " " +
                        _vm.order.vendor.lastName
                    )
                )
              ]),
              _vm._v(" "),
              _c("p", [_vm._v("Email: " + _vm._s(_vm.order.vendor.email))]),
              _vm._v(" "),
              _c("p", [_vm._v("Phone: " + _vm._s(_vm.order.vendor.phone))])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-3 text-left medium-text" }, [
              _c("p", [_c("small", [_vm._v("Shipping Detail:")])]),
              _vm._v(" "),
              _c("p", [
                _c("strong", [_vm._v(_vm._s(_vm.order.location_area))])
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v("Location: "),
                _c(
                  "a",
                  {
                    attrs: {
                      href:
                        "/getLocation?lat=" +
                        _vm.order.lat +
                        "&lang=" +
                        _vm.order.long,
                      target: "_blank",
                      title: "View on map"
                    }
                  },
                  [_vm._v(_vm._s(_vm.order.location))]
                )
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v(
                  "Nearest Landmark: " +
                    _vm._s(
                      _vm.order.nearestLandmark
                        ? _vm.order.nearestLandmark
                        : "-"
                    )
                )
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v("Alternate Name: " + _vm._s(_vm.order.alyernateName))
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v("Alternate Phone: " + _vm._s(_vm.order.alyernatePhone))
              ]),
              _vm._v(" "),
              _c("p", [
                _vm._v("Shipping Applied: " + _vm._s(_vm.order.shipping_charge))
              ])
            ]),
            _vm._v(" "),
            _vm.order.delivery != null
              ? _c("div", { staticClass: "col-md-3 text-left medium-text" }, [
                  _c("p", [_c("small", [_vm._v("Delivery Rider:")])]),
                  _vm._v(" "),
                  _c("p", [
                    _c("strong", [
                      _vm._v(
                        _vm._s(
                          _vm.order.delivery.orderNo
                            ? _vm.order.delivery.orderNo
                            : "-"
                        )
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Name: " +
                        _vm._s(
                          (_vm.order.delivery.driver.firstName
                            ? _vm.order.delivery.driver.firstName
                            : "-") +
                            " " +
                            (_vm.order.delivery.driver.lastName
                              ? _vm.order.delivery.driver.lastName
                              : "-")
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Phone: " +
                        _vm._s(
                          _vm.order.delivery.driver.phone
                            ? _vm.order.delivery.driver.phone
                            : "-"
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Vehicle Type: " +
                        _vm._s(
                          _vm.order.delivery.driver.vehicleType
                            ? _vm.order.delivery.driver.vehicleType
                            :  true
                            ? _vm.order.delivery.driver.color
                            : undefined
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Vehicle No: " +
                        _vm._s(
                          _vm.order.delivery.driver.vehicleNo
                            ? _vm.order.delivery.driver.vehicleNo
                            : "-"
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _c("p", [
                    _vm._v(
                      "Shipping Collection: " +
                        _vm._s(
                          _vm.order.shipping_charge > 0
                            ? _vm.order.shipping_charge + "(Assigned)"
                            : _vm.order.additionalDetail.shipping_charge
                            ? _vm.order.additionalDetail.shipping_charge
                            : "-" + "(Unassigned)"
                        )
                    )
                  ])
                ])
              : _c("div", { staticClass: "col-md-3 text-left medium-text" }, [
                  _c("p", [_c("small", [_vm._v("Delivery Rider:")])]),
                  _vm._v(" "),
                  _c("p", [_c("strong", [_vm._v("Not Assigned")])])
                ]),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "col-md-2 text-left medium-text",
                staticStyle: { background: ":#f8f8f8" }
              },
              [
                _c("p", [_c("small", [_vm._v("Additional Detail:")])]),
                _vm._v(" "),
                _c("p", [
                  _c("strong", [
                    _vm._v(
                      "Order Ref. Has " +
                        _vm._s(_vm.order.countOrderRef) +
                        " Orders"
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Promo Code: " +
                      _vm._s(
                        _vm.order.additionalDetail.coupon_code
                          ? _vm.order.additionalDetail.coupon_code
                          : "-"
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Promo Amount: " +
                      _vm._s(
                        _vm.order.additionalDetail.coupon_discount
                          ? _vm.order.additionalDetail.coupon_discount
                          : "-"
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "gogoReward Redeem: " +
                      _vm._s(
                        _vm._f("commaNumberFormat")(
                          _vm.order.additionalDetail.gogo_reward_redeem
                        )
                      ) +
                      " "
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Cashback: " +
                      _vm._s(
                        _vm.order.additionalDetail.order_cashback
                          ? _vm.order.additionalDetail.order_cashback
                          : "-"
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Donation: " +
                      _vm._s(
                        _vm.order.additionalDetail.donation
                          ? _vm.order.additionalDetail.donation
                          : "-"
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Shipping Charge: " +
                      _vm._s(
                        _vm._f("commaNumberFormat")(
                          _vm.order.additionalDetail.shipping_charge
                        )
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Amount Total: " +
                      _vm._s(
                        _vm._f("commaNumberFormat")(
                          _vm.order.additionalDetail.order_total
                        )
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Amount Collected : " +
                      _vm._s(
                        _vm._f("commaNumberFormat")(
                          _vm.order.additionalDetail.total_collected
                        )
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "Amount Refunded: " +
                      _vm._s(
                        _vm._f("commaNumberFormat")(
                          _vm.order.additionalDetail.total_refunded
                        )
                      )
                  )
                ]),
                _vm._v(" "),
                _c("p", [
                  _c("strong", [
                    _vm._v(
                      "Receivable: " +
                        _vm._s(
                          (_vm.order.additionalDetail.order_total
                            ? _vm.order.additionalDetail.order_total
                            : 0) -
                            (_vm.order.additionalDetail.total_collected
                              ? _vm.order.additionalDetail.total_collected
                              : 0)
                        )
                    )
                  ])
                ])
              ]
            )
          ])
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "card" }, [
        _c("div", { staticClass: "card-body" }, [
          _c("div", { staticClass: "table-responsive medium-text" }, [
            _c("table", { staticClass: "table" }, [
              _vm._m(0),
              _vm._v(" "),
              _c(
                "tbody",
                [
                  _vm._l(_vm.order.items, function(item, index1) {
                    return _c(
                      "tr",
                      { key: index1, attrs: { title: "Rs. " + item.price } },
                      [
                        _c("td", { attrs: { width: "40%" } }, [
                          _c("img", {
                            staticStyle: {
                              width: "50px",
                              height: "50px",
                              "border-radius": "50%"
                            },
                            attrs: { src: item.product.image50 }
                          }),
                          _vm._v(
                            "\n                    " +
                              _vm._s(item.name) +
                              " / " +
                              _vm._s(item.product.code) +
                              " "
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(_vm._s(item.size) + " / " + _vm._s(item.color))
                        ]),
                        _vm._v(" "),
                        _c("td", [_vm._v(_vm._s(item.quantity))]),
                        _vm._v(" "),
                        _c("td", [_vm._v(_vm._s(item.price))]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(
                            _vm._s(
                              item.discount_type === "amount"
                                ? item.discount
                                : Math.round(item.price * (item.discount / 100))
                            )
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(
                            _vm._s(
                              _vm.order.user.eliteUser ? item.elitePrice : 0
                            )
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", [_vm._v(_vm._s(item.serviceChargeAmt))]),
                        _vm._v(" "),
                        _c("td", [_vm._v(_vm._s(item.taxAmt))]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(
                            _vm._s(
                              _vm._f("commaNumberFormat")(
                                item.price +
                                  item.serviceChargeAmt +
                                  item.taxAmt -
                                  (item.discount_type == "amount"
                                    ? item.discount
                                    : Math.round(
                                        item.price * (item.discount / 100)
                                      )) -
                                  (_vm.order.user.eliteUser
                                    ? item.elitePrice
                                    : 0)
                              )
                            )
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", { attrs: { width: "10%" } }, [
                          _vm._v(
                            "Rs. " +
                              _vm._s(
                                _vm._f("commaNumberFormat")(
                                  (item.price +
                                    item.serviceChargeAmt +
                                    item.taxAmt -
                                    (item.discount_type == "amount"
                                      ? item.discount
                                      : Math.round(
                                          item.price * (item.discount / 100)
                                        )) -
                                    (_vm.order.user.eliteUser
                                      ? item.elitePrice
                                      : 0)) *
                                    item.quantity
                                )
                              )
                          )
                        ])
                      ]
                    )
                  }),
                  _vm._v(" "),
                  _vm.order.shipping_charge > 0
                    ? _c("tr", [
                        _c(
                          "td",
                          {
                            staticClass: "text-right",
                            attrs: { colspan: "9" }
                          },
                          [_vm._v("Shipping Charge")]
                        ),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v("Rs. " + _vm._s(_vm.order.shipping_charge))
                        ])
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _c("tr", [
                    _c(
                      "td",
                      { staticClass: "text-right", attrs: { colspan: "9" } },
                      [_vm._v("Total")]
                    ),
                    _vm._v(" "),
                    _c("td", [
                      _vm._v(
                        "Rs. " +
                          _vm._s(_vm._f("commaNumberFormat")(_vm.order.total))
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c(
                      "td",
                      { staticClass: "text-right", attrs: { colspan: "9" } },
                      [_vm._v("Refundable Amount")]
                    ),
                    _vm._v(" "),
                    _c("td", [
                      _vm._v(
                        "Rs. " +
                          _vm._s(
                            _vm._f("commaNumberFormat")(
                              _vm.order.refundableAmount
                            )
                          )
                      )
                    ])
                  ])
                ],
                2
              )
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _vm.assocOrders.length != 0
        ? _c("app-card", { attrs: { title: "Associated Orders" } }, [
            _c(
              "ul",
              { staticClass: "list-group-flush list-group" },
              [
                _vm._l(_vm.assocOrders, function(order) {
                  return _c(
                    "li",
                    {
                      key: order.id,
                      staticClass:
                        "list-group-item d-flex justify-content-between align-items-center list-group-item-action",
                      attrs: { waves: "" }
                    },
                    [
                      _c(
                        "router-link",
                        {
                          attrs: {
                            to: {
                              name: "order.detail",
                              params: { id: order.id }
                            },
                            title: "View Detail"
                          }
                        },
                        [
                          _c("span", [
                            _vm._v(
                              _vm._s(order.vendor.businessName) +
                                " " +
                                _vm._s(order.orderNo)
                            )
                          ])
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "span",
                        { class: "badge badge-pill " + order.status },
                        [_vm._v(" " + _vm._s(order.status))]
                      ),
                      _vm._v(" "),
                      _c("span", { staticClass: "badge badge-pill" }, [
                        _vm._v(
                          "\n            Ship. " +
                            _vm._s(order.shipping_charge) +
                            "\n        "
                        )
                      ]),
                      _vm._v(" "),
                      _c("span", { staticClass: "badge badge-pill" }, [
                        _vm._v(
                          "\n            Rs. " +
                            _vm._s(order.total) +
                            "\n        "
                        )
                      ])
                    ],
                    1
                  )
                }),
                _vm._v(" "),
                _vm.assocOrders.length == 0
                  ? _c(
                      "li",
                      {
                        staticClass:
                          "list-group-item d-flex justify-content-between align-items-center list-group-item-action",
                        attrs: { waves: "" }
                      },
                      [_vm._v(" Not Listed")]
                    )
                  : _vm._e()
              ],
              2
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.orderFeedbacks.length != 0
        ? _c(
            "app-card",
            { attrs: { title: "Order Feedback" } },
            _vm._l(_vm.orderFeedbacks, function(feedback) {
              return _c("div", { key: feedback.id }, [
                _c("div", { staticClass: "media" }, [
                  _c("div", { staticClass: "media-left" }, [
                    _c("img", {
                      staticClass: "media-object",
                      staticStyle: { width: "40px" },
                      attrs: { src: _vm.order.user.image }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "media-body" }, [
                    _c("h4", { staticClass: "media-heading title" }, [
                      _vm._v(
                        _vm._s(
                          _vm.order.user.firstName +
                            " " +
                            _vm.order.user.lastName
                        )
                      )
                    ]),
                    _vm._v(" "),
                    _c("p", { staticClass: "komen" }, [
                      _vm._v(
                        "\n                    " + _vm._s(feedback.feedback)
                      ),
                      _c("br"),
                      _vm._v(" "),
                      _c("small", [_vm._v("On: " + _vm._s(feedback.createdAt))])
                    ])
                  ])
                ]),
                _vm._v(" "),
                feedback.respond != null
                  ? _c("div", { staticClass: "geser" }, [
                      _c("div", { staticClass: "media" }, [
                        _c("div", { staticClass: "media-left" }, [
                          _c("img", {
                            staticClass: "media-object",
                            staticStyle: { width: "40px" },
                            attrs: {
                              src:
                                feedback.admin != null
                                  ? feedback.admin.image
                                  : ""
                            }
                          })
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "media-body" }, [
                          _c("h4", { staticClass: "media-heading title" }, [
                            _vm._v(
                              _vm._s(
                                feedback.admin != null
                                  ? feedback.admin.name
                                  : ""
                              )
                            )
                          ]),
                          _vm._v(" "),
                          _c("p", { staticClass: "komen" }, [
                            _vm._v(
                              "\n                    " +
                                _vm._s(feedback.respond)
                            ),
                            _c("br"),
                            _vm._v(" "),
                            _c("small", [
                              _vm._v("On. " + _vm._s(feedback.updatedAt))
                            ])
                          ])
                        ])
                      ])
                    ])
                  : _vm._e()
              ])
            }),
            0
          )
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("Product")]),
        _vm._v(" "),
        _c("th", [_vm._v("Size / Color")]),
        _vm._v(" "),
        _c("th", [_vm._v("Qty")]),
        _vm._v(" "),
        _c("th", [_vm._v("Price")]),
        _vm._v(" "),
        _c("th", [_vm._v("Discount")]),
        _vm._v(" "),
        _c("th", [_vm._v("Elite Discount")]),
        _vm._v(" "),
        _c("th", [_vm._v("Service Charge")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tax Amount")]),
        _vm._v(" "),
        _c("th", [_vm._v("Selling price")]),
        _vm._v(" "),
        _c("th", [_vm._v("Sub Total")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/pages/order/OrderDetail.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/pages/order/OrderDetail.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OrderDetail.vue?vue&type=template&id=543b9058& */ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058&");
/* harmony import */ var _OrderDetail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OrderDetail.vue?vue&type=script&lang=js& */ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OrderDetail.vue?vue&type=style&index=0&lang=css& */ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _OrderDetail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__["render"],
  _OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pages/order/OrderDetail.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./OrderDetail.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css& ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./OrderDetail.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./OrderDetail.vue?vue&type=template&id=543b9058& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/order/OrderDetail.vue?vue&type=template&id=543b9058&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderDetail_vue_vue_type_template_id_543b9058___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);