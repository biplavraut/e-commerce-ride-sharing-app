(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_models_Reports__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @utils/models/Reports */ "./resources/js/utils/models/Reports.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _routes_admin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @routes/admin */ "./resources/js/routes/admin.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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
  name: "ReportIndex",
  data: function data() {
    var _this$$route$params$u;

    return {
      filteredDataTable: [],
      dataTable: [],
      userTransactionsData: [],
      ridersData: [{
        blocked: 10,
        active: 20,
        inactive: 30,
        top: 18
      }],
      userPaymentLog: [],
      search: "",
      usersList: [],
      userId: (_this$$route$params$u = this.$route.params.userId) !== null && _this$$route$params$u !== void 0 ? _this$$route$params$u : 0,
      userFullName: '',
      userPhone: '',
      model: new _utils_models_Reports__WEBPACK_IMPORTED_MODULE_0__["default"](),
      filter: 'today',
      from: '',
      to: '',
      filter_from: '',
      filter_to: ''
    };
  },
  methods: {
    // Filter Data on basis of time
    filterData: function filterData(type) {
      this.from = '';
      this.to = '';
      this.filter = type;
      this.loadData();
    },
    checkDate: function checkDate() {
      var _this = this;

      if (this.from != '' && this.to != '') {
        this.filter = 'custom';
        this.filter_from = this.from;
        this.filter_to = this.to;
        var q = 'custom';
        axios.get(this.model.indexUrl + "/app-user-transactions?q=" + q + '&from=' + this.from + '&to=' + this.to + '&user=' + this.userId).then(function (response) {
          _this.userTransactionsData = response.data.data;
          _this.userPaymentLog = response.data.payments;
          _this.userFullName = response.data.user.first_name + ' ' + response.data.user.last_name;
          _this.userPhone = response.data.user.phone;
          $("#myDataTable").DataTable().destroy();

          _this.transactionDataTable();

          $("#paymentDataTable").DataTable().destroy();

          _this.paymentsDataTable(); // Function for Data Preparation

        });
      }
    },
    // Initial Load of data
    loadData: function loadData() {
      var _this2 = this;

      var q = this.filter;
      axios.get(this.model.indexUrl + "/app-user-transactions?q=" + q + '&from=' + this.from + '&to=' + this.to + '&user=' + this.userId).then(function (response) {
        _this2.userTransactionsData = response.data.data;
        _this2.userPaymentLog = response.data.payments;
        _this2.userFullName = response.data.user.first_name + ' ' + response.data.user.last_name;
        _this2.userPhone = response.data.user.phone;

        _this2.transactionDataTable(); // Function for Data Preparation

      });
    },
    // End of Initial Load of Data
    displayTransactions: function displayTransactions(userId) {
      var _this3 = this;

      this.userId = userId;
      var q = this.filter;
      axios.get(this.model.indexUrl + "/app-user-transactions?q=" + q + '&from=' + this.from + '&to=' + this.to + '&user=' + userId).then(function (response) {
        _this3.userTransactionsData = response.data.data;
        _this3.userPaymentLog = response.data.payments;
        _this3.userFullName = response.data.user.first_name + ' ' + response.data.user.last_name;
        _this3.userPhone = response.data.user.phone;
        $("#myDataTable").DataTable().destroy();

        _this3.transactionDataTable(); // Function for Data Preparation


        $("#paymentDataTable").DataTable().destroy();

        _this3.paymentsDataTable(); // Function for Data Preparation            

      });
    },
    // Updating DataTable
    transactionDataTable: function transactionDataTable() {
      if ($.fn.DataTable.isDataTable('#myDataTable')) {
        $("#myDataTable").DataTable().destroy();
      }

      $("#myDataTable").DataTable({
        dom: 'Bfrtip',
        // buttons: ['excel','pdf'],
        buttons: ['excel', {
          text: 'PDF',
          extend: 'pdfHtml5',
          message: '',
          orientation: 'potrait',
          exportOptions: {
            columns: ':visible'
          },
          customize: function customize(doc) {
            var today = new Date();
            var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
            doc.pageMargins = [40, 60, 60, 40];
            doc.defaultStyle.fontSize = 7;
            doc.styles.tableHeader.fontSize = 10;
            doc.styles.title.fontSize = 12;
            doc.styles.title.alignment = 'left'; // Remove spaces around page title

            doc.content[0].text = doc.content[0].text.trim(); // Create a Header

            doc['header'] = function (page, pages) {
              return {
                columns: ['GOGO20 | HIGHLY CONFIDENTIAL | ', {
                  text: 'FOR AUTHORIZED CIRCULATIONS ONLY',
                  fontSize: 8
                }, {
                  // This is the right column
                  alignment: 'right',
                  text: ['Date: ', {
                    text: date
                  }],
                  color: '#000',
                  background: '#fff',
                  fontSize: 8
                }],
                margin: [40, 40],
                color: '#ad0b00',
                fontSize: 10
              };
            }; // Styling the table: create style object


            var objLayout = {}; // Horizontal line thickness

            objLayout['hLineWidth'] = function (i) {
              return .5;
            }; // Vertikal line thickness


            objLayout['vLineWidth'] = function (i) {
              return .5;
            }; // Horizontal line color


            objLayout['hLineColor'] = function (i) {
              return '#aaa';
            }; // Vertical line color


            objLayout['vLineColor'] = function (i) {
              return '#aaa';
            }; // Left padding of the cell


            objLayout['paddingLeft'] = function (i) {
              return 4;
            }; // Right padding of the cell


            objLayout['paddingRight'] = function (i) {
              return 4;
            }; // Inject the object in the document


            doc.content[1].layout = objLayout;
          }
        }],
        pageLength: 20,
        data: this.userTransactionsData,
        columns: [{
          data: 'from'
        }, {
          data: 'paymentMode'
        }, {
          data: 'type'
        }, {
          data: 'point'
        }, {
          data: 'date',
          render: function render(data, type, row) {
            return moment__WEBPACK_IMPORTED_MODULE_1___default()(data).format("LLLL");
          }
        }]
      });
    },
    paymentsDataTable: function paymentsDataTable() {
      if ($.fn.DataTable.isDataTable('#paymentDataTable')) {
        $("#paymentDataTable").DataTable().destroy();
      }

      $("#paymentDataTable").DataTable({
        dom: 'Bfrtip',
        // buttons: ['excel','pdf'],
        buttons: ['excel', {
          text: 'PDF',
          extend: 'pdfHtml5',
          message: '',
          orientation: 'potrait',
          exportOptions: {
            columns: ':visible'
          },
          customize: function customize(doc) {
            var today = new Date();
            var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
            doc.pageMargins = [40, 60, 60, 40];
            doc.defaultStyle.fontSize = 7;
            doc.styles.tableHeader.fontSize = 10;
            doc.styles.title.fontSize = 12;
            doc.styles.title.alignment = 'left'; // Remove spaces around page title

            doc.content[0].text = doc.content[0].text.trim(); // Create a Header

            doc['header'] = function (page, pages) {
              return {
                columns: ['GOGO20 | HIGHLY CONFIDENTIAL | ', {
                  text: 'FOR AUTHORIZED CIRCULATIONS ONLY',
                  fontSize: 8
                }, {
                  // This is the right column
                  alignment: 'right',
                  text: ['Date: ', {
                    text: date
                  }],
                  color: '#000',
                  background: '#fff',
                  fontSize: 8
                }],
                margin: [40, 40],
                color: '#ad0b00',
                fontSize: 10
              };
            }; // Styling the table: create style object


            var objLayout = {}; // Horizontal line thickness

            objLayout['hLineWidth'] = function (i) {
              return .5;
            }; // Vertikal line thickness


            objLayout['vLineWidth'] = function (i) {
              return .5;
            }; // Horizontal line color


            objLayout['hLineColor'] = function (i) {
              return '#aaa';
            }; // Vertical line color


            objLayout['vLineColor'] = function (i) {
              return '#aaa';
            }; // Left padding of the cell


            objLayout['paddingLeft'] = function (i) {
              return 4;
            }; // Right padding of the cell


            objLayout['paddingRight'] = function (i) {
              return 4;
            }; // Inject the object in the document


            doc.content[1].layout = objLayout;
          }
        }],
        pageLength: 20,
        data: this.userPaymentLog,
        columns: [{
          data: 'action'
        }, {
          data: 'payment_mode'
        }, {
          data: 'type'
        }, {
          data: 'bill_amt'
        }, {
          data: 'created_at',
          render: function render(data, type, row) {
            return moment__WEBPACK_IMPORTED_MODULE_1___default()(data).format("LL");
          }
        }]
      });
    },
    // End of Updating DataTable
    searchUser: function searchUser() {
      var _this4 = this;

      var q = this.search;
      axios.get("/admin/user/get-data?name=" + q // "/admin/find-user?q=" + q
      ).then(function (response) {
        _this4.usersList = response.data.data;
      })["catch"](function () {});
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_2__["mapGetters"])(["authUser", "homePageCounts"])),
  mounted: function mounted() {
    if (this.$route.params[0]) {
      alertMessage("404, Page not found !!!", "danger");
    }
  },
  created: function created() {
    if (this.userId != 0) {
      this.loadData();
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\ndiv.dataTables_wrapper div.dataTables_filter {\n    text-align: left !important;\n}\ndiv.dataTables_wrapper div.dataTables_filter input {\n    width: 55em !important;\n}\ndiv.dataTables_wrapper div.dt-buttons {\n  float: right;\n}\ndiv.dataTables_wrapper div.dt-buttons button {\n    margin: 0px 10px !important;\n}\n.buttons-excel{\n  background-color: #1f6e43 !important;\n}\n.buttons-pdf{\n  background-color: #ad0b00 !important;\n}\n.table td {\n   text-align: center;\n}\n.table th {\n   height: 40px !important;\n}\n/* table.fixedHeader-floating{position:fixed !important;background-color:white}table.fixedHeader-floating.no-footer{border-bottom-width:0}table.fixedHeader-locked{position:absolute !important;background-color:white}@media print{table.fixedHeader-floating{display:none}} */\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/moment/locale sync recursive ^\\.\\/.*$":
/*!**************************************************!*\
  !*** ./node_modules/moment/locale sync ^\.\/.*$ ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./af": "./node_modules/moment/locale/af.js",
	"./af.js": "./node_modules/moment/locale/af.js",
	"./ar": "./node_modules/moment/locale/ar.js",
	"./ar-dz": "./node_modules/moment/locale/ar-dz.js",
	"./ar-dz.js": "./node_modules/moment/locale/ar-dz.js",
	"./ar-kw": "./node_modules/moment/locale/ar-kw.js",
	"./ar-kw.js": "./node_modules/moment/locale/ar-kw.js",
	"./ar-ly": "./node_modules/moment/locale/ar-ly.js",
	"./ar-ly.js": "./node_modules/moment/locale/ar-ly.js",
	"./ar-ma": "./node_modules/moment/locale/ar-ma.js",
	"./ar-ma.js": "./node_modules/moment/locale/ar-ma.js",
	"./ar-sa": "./node_modules/moment/locale/ar-sa.js",
	"./ar-sa.js": "./node_modules/moment/locale/ar-sa.js",
	"./ar-tn": "./node_modules/moment/locale/ar-tn.js",
	"./ar-tn.js": "./node_modules/moment/locale/ar-tn.js",
	"./ar.js": "./node_modules/moment/locale/ar.js",
	"./az": "./node_modules/moment/locale/az.js",
	"./az.js": "./node_modules/moment/locale/az.js",
	"./be": "./node_modules/moment/locale/be.js",
	"./be.js": "./node_modules/moment/locale/be.js",
	"./bg": "./node_modules/moment/locale/bg.js",
	"./bg.js": "./node_modules/moment/locale/bg.js",
	"./bm": "./node_modules/moment/locale/bm.js",
	"./bm.js": "./node_modules/moment/locale/bm.js",
	"./bn": "./node_modules/moment/locale/bn.js",
	"./bn.js": "./node_modules/moment/locale/bn.js",
	"./bo": "./node_modules/moment/locale/bo.js",
	"./bo.js": "./node_modules/moment/locale/bo.js",
	"./br": "./node_modules/moment/locale/br.js",
	"./br.js": "./node_modules/moment/locale/br.js",
	"./bs": "./node_modules/moment/locale/bs.js",
	"./bs.js": "./node_modules/moment/locale/bs.js",
	"./ca": "./node_modules/moment/locale/ca.js",
	"./ca.js": "./node_modules/moment/locale/ca.js",
	"./cs": "./node_modules/moment/locale/cs.js",
	"./cs.js": "./node_modules/moment/locale/cs.js",
	"./cv": "./node_modules/moment/locale/cv.js",
	"./cv.js": "./node_modules/moment/locale/cv.js",
	"./cy": "./node_modules/moment/locale/cy.js",
	"./cy.js": "./node_modules/moment/locale/cy.js",
	"./da": "./node_modules/moment/locale/da.js",
	"./da.js": "./node_modules/moment/locale/da.js",
	"./de": "./node_modules/moment/locale/de.js",
	"./de-at": "./node_modules/moment/locale/de-at.js",
	"./de-at.js": "./node_modules/moment/locale/de-at.js",
	"./de-ch": "./node_modules/moment/locale/de-ch.js",
	"./de-ch.js": "./node_modules/moment/locale/de-ch.js",
	"./de.js": "./node_modules/moment/locale/de.js",
	"./dv": "./node_modules/moment/locale/dv.js",
	"./dv.js": "./node_modules/moment/locale/dv.js",
	"./el": "./node_modules/moment/locale/el.js",
	"./el.js": "./node_modules/moment/locale/el.js",
	"./en-SG": "./node_modules/moment/locale/en-SG.js",
	"./en-SG.js": "./node_modules/moment/locale/en-SG.js",
	"./en-au": "./node_modules/moment/locale/en-au.js",
	"./en-au.js": "./node_modules/moment/locale/en-au.js",
	"./en-ca": "./node_modules/moment/locale/en-ca.js",
	"./en-ca.js": "./node_modules/moment/locale/en-ca.js",
	"./en-gb": "./node_modules/moment/locale/en-gb.js",
	"./en-gb.js": "./node_modules/moment/locale/en-gb.js",
	"./en-ie": "./node_modules/moment/locale/en-ie.js",
	"./en-ie.js": "./node_modules/moment/locale/en-ie.js",
	"./en-il": "./node_modules/moment/locale/en-il.js",
	"./en-il.js": "./node_modules/moment/locale/en-il.js",
	"./en-nz": "./node_modules/moment/locale/en-nz.js",
	"./en-nz.js": "./node_modules/moment/locale/en-nz.js",
	"./eo": "./node_modules/moment/locale/eo.js",
	"./eo.js": "./node_modules/moment/locale/eo.js",
	"./es": "./node_modules/moment/locale/es.js",
	"./es-do": "./node_modules/moment/locale/es-do.js",
	"./es-do.js": "./node_modules/moment/locale/es-do.js",
	"./es-us": "./node_modules/moment/locale/es-us.js",
	"./es-us.js": "./node_modules/moment/locale/es-us.js",
	"./es.js": "./node_modules/moment/locale/es.js",
	"./et": "./node_modules/moment/locale/et.js",
	"./et.js": "./node_modules/moment/locale/et.js",
	"./eu": "./node_modules/moment/locale/eu.js",
	"./eu.js": "./node_modules/moment/locale/eu.js",
	"./fa": "./node_modules/moment/locale/fa.js",
	"./fa.js": "./node_modules/moment/locale/fa.js",
	"./fi": "./node_modules/moment/locale/fi.js",
	"./fi.js": "./node_modules/moment/locale/fi.js",
	"./fo": "./node_modules/moment/locale/fo.js",
	"./fo.js": "./node_modules/moment/locale/fo.js",
	"./fr": "./node_modules/moment/locale/fr.js",
	"./fr-ca": "./node_modules/moment/locale/fr-ca.js",
	"./fr-ca.js": "./node_modules/moment/locale/fr-ca.js",
	"./fr-ch": "./node_modules/moment/locale/fr-ch.js",
	"./fr-ch.js": "./node_modules/moment/locale/fr-ch.js",
	"./fr.js": "./node_modules/moment/locale/fr.js",
	"./fy": "./node_modules/moment/locale/fy.js",
	"./fy.js": "./node_modules/moment/locale/fy.js",
	"./ga": "./node_modules/moment/locale/ga.js",
	"./ga.js": "./node_modules/moment/locale/ga.js",
	"./gd": "./node_modules/moment/locale/gd.js",
	"./gd.js": "./node_modules/moment/locale/gd.js",
	"./gl": "./node_modules/moment/locale/gl.js",
	"./gl.js": "./node_modules/moment/locale/gl.js",
	"./gom-latn": "./node_modules/moment/locale/gom-latn.js",
	"./gom-latn.js": "./node_modules/moment/locale/gom-latn.js",
	"./gu": "./node_modules/moment/locale/gu.js",
	"./gu.js": "./node_modules/moment/locale/gu.js",
	"./he": "./node_modules/moment/locale/he.js",
	"./he.js": "./node_modules/moment/locale/he.js",
	"./hi": "./node_modules/moment/locale/hi.js",
	"./hi.js": "./node_modules/moment/locale/hi.js",
	"./hr": "./node_modules/moment/locale/hr.js",
	"./hr.js": "./node_modules/moment/locale/hr.js",
	"./hu": "./node_modules/moment/locale/hu.js",
	"./hu.js": "./node_modules/moment/locale/hu.js",
	"./hy-am": "./node_modules/moment/locale/hy-am.js",
	"./hy-am.js": "./node_modules/moment/locale/hy-am.js",
	"./id": "./node_modules/moment/locale/id.js",
	"./id.js": "./node_modules/moment/locale/id.js",
	"./is": "./node_modules/moment/locale/is.js",
	"./is.js": "./node_modules/moment/locale/is.js",
	"./it": "./node_modules/moment/locale/it.js",
	"./it-ch": "./node_modules/moment/locale/it-ch.js",
	"./it-ch.js": "./node_modules/moment/locale/it-ch.js",
	"./it.js": "./node_modules/moment/locale/it.js",
	"./ja": "./node_modules/moment/locale/ja.js",
	"./ja.js": "./node_modules/moment/locale/ja.js",
	"./jv": "./node_modules/moment/locale/jv.js",
	"./jv.js": "./node_modules/moment/locale/jv.js",
	"./ka": "./node_modules/moment/locale/ka.js",
	"./ka.js": "./node_modules/moment/locale/ka.js",
	"./kk": "./node_modules/moment/locale/kk.js",
	"./kk.js": "./node_modules/moment/locale/kk.js",
	"./km": "./node_modules/moment/locale/km.js",
	"./km.js": "./node_modules/moment/locale/km.js",
	"./kn": "./node_modules/moment/locale/kn.js",
	"./kn.js": "./node_modules/moment/locale/kn.js",
	"./ko": "./node_modules/moment/locale/ko.js",
	"./ko.js": "./node_modules/moment/locale/ko.js",
	"./ku": "./node_modules/moment/locale/ku.js",
	"./ku.js": "./node_modules/moment/locale/ku.js",
	"./ky": "./node_modules/moment/locale/ky.js",
	"./ky.js": "./node_modules/moment/locale/ky.js",
	"./lb": "./node_modules/moment/locale/lb.js",
	"./lb.js": "./node_modules/moment/locale/lb.js",
	"./lo": "./node_modules/moment/locale/lo.js",
	"./lo.js": "./node_modules/moment/locale/lo.js",
	"./lt": "./node_modules/moment/locale/lt.js",
	"./lt.js": "./node_modules/moment/locale/lt.js",
	"./lv": "./node_modules/moment/locale/lv.js",
	"./lv.js": "./node_modules/moment/locale/lv.js",
	"./me": "./node_modules/moment/locale/me.js",
	"./me.js": "./node_modules/moment/locale/me.js",
	"./mi": "./node_modules/moment/locale/mi.js",
	"./mi.js": "./node_modules/moment/locale/mi.js",
	"./mk": "./node_modules/moment/locale/mk.js",
	"./mk.js": "./node_modules/moment/locale/mk.js",
	"./ml": "./node_modules/moment/locale/ml.js",
	"./ml.js": "./node_modules/moment/locale/ml.js",
	"./mn": "./node_modules/moment/locale/mn.js",
	"./mn.js": "./node_modules/moment/locale/mn.js",
	"./mr": "./node_modules/moment/locale/mr.js",
	"./mr.js": "./node_modules/moment/locale/mr.js",
	"./ms": "./node_modules/moment/locale/ms.js",
	"./ms-my": "./node_modules/moment/locale/ms-my.js",
	"./ms-my.js": "./node_modules/moment/locale/ms-my.js",
	"./ms.js": "./node_modules/moment/locale/ms.js",
	"./mt": "./node_modules/moment/locale/mt.js",
	"./mt.js": "./node_modules/moment/locale/mt.js",
	"./my": "./node_modules/moment/locale/my.js",
	"./my.js": "./node_modules/moment/locale/my.js",
	"./nb": "./node_modules/moment/locale/nb.js",
	"./nb.js": "./node_modules/moment/locale/nb.js",
	"./ne": "./node_modules/moment/locale/ne.js",
	"./ne.js": "./node_modules/moment/locale/ne.js",
	"./nl": "./node_modules/moment/locale/nl.js",
	"./nl-be": "./node_modules/moment/locale/nl-be.js",
	"./nl-be.js": "./node_modules/moment/locale/nl-be.js",
	"./nl.js": "./node_modules/moment/locale/nl.js",
	"./nn": "./node_modules/moment/locale/nn.js",
	"./nn.js": "./node_modules/moment/locale/nn.js",
	"./pa-in": "./node_modules/moment/locale/pa-in.js",
	"./pa-in.js": "./node_modules/moment/locale/pa-in.js",
	"./pl": "./node_modules/moment/locale/pl.js",
	"./pl.js": "./node_modules/moment/locale/pl.js",
	"./pt": "./node_modules/moment/locale/pt.js",
	"./pt-br": "./node_modules/moment/locale/pt-br.js",
	"./pt-br.js": "./node_modules/moment/locale/pt-br.js",
	"./pt.js": "./node_modules/moment/locale/pt.js",
	"./ro": "./node_modules/moment/locale/ro.js",
	"./ro.js": "./node_modules/moment/locale/ro.js",
	"./ru": "./node_modules/moment/locale/ru.js",
	"./ru.js": "./node_modules/moment/locale/ru.js",
	"./sd": "./node_modules/moment/locale/sd.js",
	"./sd.js": "./node_modules/moment/locale/sd.js",
	"./se": "./node_modules/moment/locale/se.js",
	"./se.js": "./node_modules/moment/locale/se.js",
	"./si": "./node_modules/moment/locale/si.js",
	"./si.js": "./node_modules/moment/locale/si.js",
	"./sk": "./node_modules/moment/locale/sk.js",
	"./sk.js": "./node_modules/moment/locale/sk.js",
	"./sl": "./node_modules/moment/locale/sl.js",
	"./sl.js": "./node_modules/moment/locale/sl.js",
	"./sq": "./node_modules/moment/locale/sq.js",
	"./sq.js": "./node_modules/moment/locale/sq.js",
	"./sr": "./node_modules/moment/locale/sr.js",
	"./sr-cyrl": "./node_modules/moment/locale/sr-cyrl.js",
	"./sr-cyrl.js": "./node_modules/moment/locale/sr-cyrl.js",
	"./sr.js": "./node_modules/moment/locale/sr.js",
	"./ss": "./node_modules/moment/locale/ss.js",
	"./ss.js": "./node_modules/moment/locale/ss.js",
	"./sv": "./node_modules/moment/locale/sv.js",
	"./sv.js": "./node_modules/moment/locale/sv.js",
	"./sw": "./node_modules/moment/locale/sw.js",
	"./sw.js": "./node_modules/moment/locale/sw.js",
	"./ta": "./node_modules/moment/locale/ta.js",
	"./ta.js": "./node_modules/moment/locale/ta.js",
	"./te": "./node_modules/moment/locale/te.js",
	"./te.js": "./node_modules/moment/locale/te.js",
	"./tet": "./node_modules/moment/locale/tet.js",
	"./tet.js": "./node_modules/moment/locale/tet.js",
	"./tg": "./node_modules/moment/locale/tg.js",
	"./tg.js": "./node_modules/moment/locale/tg.js",
	"./th": "./node_modules/moment/locale/th.js",
	"./th.js": "./node_modules/moment/locale/th.js",
	"./tl-ph": "./node_modules/moment/locale/tl-ph.js",
	"./tl-ph.js": "./node_modules/moment/locale/tl-ph.js",
	"./tlh": "./node_modules/moment/locale/tlh.js",
	"./tlh.js": "./node_modules/moment/locale/tlh.js",
	"./tr": "./node_modules/moment/locale/tr.js",
	"./tr.js": "./node_modules/moment/locale/tr.js",
	"./tzl": "./node_modules/moment/locale/tzl.js",
	"./tzl.js": "./node_modules/moment/locale/tzl.js",
	"./tzm": "./node_modules/moment/locale/tzm.js",
	"./tzm-latn": "./node_modules/moment/locale/tzm-latn.js",
	"./tzm-latn.js": "./node_modules/moment/locale/tzm-latn.js",
	"./tzm.js": "./node_modules/moment/locale/tzm.js",
	"./ug-cn": "./node_modules/moment/locale/ug-cn.js",
	"./ug-cn.js": "./node_modules/moment/locale/ug-cn.js",
	"./uk": "./node_modules/moment/locale/uk.js",
	"./uk.js": "./node_modules/moment/locale/uk.js",
	"./ur": "./node_modules/moment/locale/ur.js",
	"./ur.js": "./node_modules/moment/locale/ur.js",
	"./uz": "./node_modules/moment/locale/uz.js",
	"./uz-latn": "./node_modules/moment/locale/uz-latn.js",
	"./uz-latn.js": "./node_modules/moment/locale/uz-latn.js",
	"./uz.js": "./node_modules/moment/locale/uz.js",
	"./vi": "./node_modules/moment/locale/vi.js",
	"./vi.js": "./node_modules/moment/locale/vi.js",
	"./x-pseudo": "./node_modules/moment/locale/x-pseudo.js",
	"./x-pseudo.js": "./node_modules/moment/locale/x-pseudo.js",
	"./yo": "./node_modules/moment/locale/yo.js",
	"./yo.js": "./node_modules/moment/locale/yo.js",
	"./zh-cn": "./node_modules/moment/locale/zh-cn.js",
	"./zh-cn.js": "./node_modules/moment/locale/zh-cn.js",
	"./zh-hk": "./node_modules/moment/locale/zh-hk.js",
	"./zh-hk.js": "./node_modules/moment/locale/zh-hk.js",
	"./zh-tw": "./node_modules/moment/locale/zh-tw.js",
	"./zh-tw.js": "./node_modules/moment/locale/zh-tw.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./node_modules/moment/locale sync recursive ^\\.\\/.*$";

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./UserTransaction.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c("div", { staticStyle: { "margin-bottom": "10px", display: "flex" } }, [
      _c("ul", { staticClass: "nav nav-pills nav-pills-warning" }, [
        _c("li", { class: _vm.filter === "today" ? "active" : "nav-item" }, [
          _c(
            "a",
            {
              staticClass: "nav-link",
              attrs: { href: "#" },
              on: {
                click: function($event) {
                  return _vm.filterData("today")
                }
              }
            },
            [_vm._v("Today ")]
          )
        ]),
        _vm._v(" "),
        _c(
          "li",
          { class: _vm.filter === "yesterday" ? "active" : "nav-item" },
          [
            _c(
              "a",
              {
                staticClass: "nav-link",
                attrs: { href: "#" },
                on: {
                  click: function($event) {
                    return _vm.filterData("yesterday")
                  }
                }
              },
              [_vm._v("Yesterday ")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "li",
          { class: _vm.filter === "this-week" ? "active" : "nav-item" },
          [
            _c(
              "a",
              {
                staticClass: "nav-link",
                attrs: { href: "#" },
                on: {
                  click: function($event) {
                    return _vm.filterData("this-week")
                  }
                }
              },
              [_vm._v("This Week")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "li",
          { class: _vm.filter === "this-month" ? "active" : "nav-item" },
          [
            _c(
              "a",
              {
                attrs: { "aria-current": "true", href: "#" },
                on: {
                  click: function($event) {
                    return _vm.filterData("this-month")
                  }
                }
              },
              [_vm._v("This Month ")]
            )
          ]
        ),
        _vm._v(" "),
        _c("li", { class: _vm.filter === "custom" ? "active" : "nav-item" }, [
          _c(
            "a",
            {
              attrs: {
                "aria-current": "true",
                href: "#",
                "aria-disabled": "disabled",
                title: "Please Select Date"
              }
            },
            [_vm._v("Custom ")]
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "input-group" }, [
        _vm._m(0),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.from,
              expression: "from"
            }
          ],
          staticClass: "form-control",
          attrs: {
            type: "date",
            id: "datetimepicker6",
            "aria-describedby": "basic-addon1"
          },
          domProps: { value: _vm.from },
          on: {
            input: [
              function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.from = $event.target.value
              },
              _vm.checkDate
            ]
          }
        })
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "input-group" }, [
        _vm._m(1),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.to,
              expression: "to"
            }
          ],
          staticClass: "form-control",
          attrs: {
            type: "date",
            id: "datetimepicker7",
            "aria-describedby": "basic-addon2"
          },
          domProps: { value: _vm.to },
          on: {
            input: [
              function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.to = $event.target.value
              },
              _vm.checkDate
            ]
          }
        })
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-8" },
        [
          _vm.authUser.type !== "support" && _vm.authUser.type !== "officer"
            ? _c(
                "app-card",
                {
                  attrs: {
                    title:
                      "User Transactions: " +
                      _vm.userFullName +
                      ", <small>" +
                      _vm.userPhone +
                      "</small>"
                  }
                },
                [
                  _c("div", { staticClass: "table-responsive" }, [
                    _c(
                      "table",
                      {
                        staticClass:
                          "table table-hover table-striped table-bordered table-lg",
                        attrs: { id: "myDataTable", width: "100%" }
                      },
                      [
                        _c("thead", [
                          _c("tr", [
                            _c("th", [_vm._v("From")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Payment Mode")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Type")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Point")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Created At")])
                          ])
                        ])
                      ]
                    )
                  ])
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.authUser.type !== "support" && _vm.authUser.type !== "officer"
            ? _c(
                "app-card",
                {
                  attrs: {
                    title:
                      "User Payment Logs: " +
                      _vm.userFullName +
                      ", <small>" +
                      _vm.userPhone +
                      "</small>"
                  }
                },
                [
                  _c("div", { staticClass: "table-responsive" }, [
                    _c(
                      "table",
                      {
                        staticClass:
                          "table table-hover table-striped table-bordered table-lg",
                        attrs: { id: "paymentDataTable", width: "100%" }
                      },
                      [
                        _c("thead", [
                          _c("tr", [
                            _c("th", [_vm._v("From")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Payment Mode")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Type")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Point")]),
                            _vm._v(" "),
                            _c("th", [_vm._v("Created At")])
                          ])
                        ])
                      ]
                    )
                  ])
                ]
              )
            : _vm._e()
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-md-4" },
        [
          _c(
            "app-card",
            { attrs: { title: "Users" } },
            [
              _c("input-text", {
                attrs: { label: "Find User", name: "search-text" },
                on: { input: _vm.searchUser },
                model: {
                  value: _vm.search,
                  callback: function($$v) {
                    _vm.search = $$v
                  },
                  expression: "search"
                }
              }),
              _vm._v(" "),
              _c(
                "ul",
                { staticClass: "list-group list-group-flush" },
                _vm._l(_vm.usersList, function(item) {
                  return _c(
                    "li",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: _vm.search.trim().length > 0,
                          expression: "search.trim().length > 0"
                        }
                      ],
                      key: item.id,
                      staticClass: "list-group-item"
                    },
                    [
                      _c("div", { staticClass: "row" }, [
                        _c("div", { staticClass: "col-md-8" }, [
                          _vm._v(
                            "\n                      " +
                              _vm._s(item.firstName + " " + item.lastName) +
                              "\n                  "
                          )
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "col-md-4" }, [
                          _c("span", [
                            _c(
                              "button",
                              {
                                staticClass: "btn btn-sm btn-primary",
                                on: {
                                  click: function($event) {
                                    return _vm.displayTransactions(item.id)
                                  }
                                }
                              },
                              [
                                _vm._v(
                                  "\n                          Show\n                      "
                                )
                              ]
                            )
                          ])
                        ])
                      ])
                    ]
                  )
                }),
                0
              )
            ],
            1
          )
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "span",
      { staticClass: "input-group-addon", attrs: { id: "date-addon1" } },
      [_c("strong", [_vm._v("From")])]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "span",
      { staticClass: "input-group-addon", attrs: { id: "date-addon2" } },
      [_c("strong", [_vm._v("To")])]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/pages/report-users/UserTransaction.vue":
/*!************************************************************************!*\
  !*** ./resources/js/components/pages/report-users/UserTransaction.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UserTransaction.vue?vue&type=template&id=93d6c9ee& */ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee&");
/* harmony import */ var _UserTransaction_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UserTransaction.vue?vue&type=script&lang=js& */ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./UserTransaction.vue?vue&type=style&index=0&lang=css& */ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _UserTransaction_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pages/report-users/UserTransaction.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./UserTransaction.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css& ***!
  \*********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./UserTransaction.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./UserTransaction.vue?vue&type=template&id=93d6c9ee& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/report-users/UserTransaction.vue?vue&type=template&id=93d6c9ee&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserTransaction_vue_vue_type_template_id_93d6c9ee___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/utils/models/Reports.js":
/*!**********************************************!*\
  !*** ./resources/js/utils/models/Reports.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Report; });
/* harmony import */ var _Model__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Model */ "./resources/js/utils/models/Model.js");
/* harmony import */ var _routes_admin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @routes/admin */ "./resources/js/routes/admin.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }




var Report = /*#__PURE__*/function (_Model) {
  _inherits(Report, _Model);

  var _super = _createSuper(Report);

  function Report(data) {
    var _this;

    _classCallCheck(this, Report);

    _this = _super.call(this, data);
    _this.indexUrl = _routes_admin__WEBPACK_IMPORTED_MODULE_1__["REPORT_HOME"];
    _this.namePlural = "reports";
    _this.nameLowerCase = "report";
    return _this;
  }

  return Report;
}(_Model__WEBPACK_IMPORTED_MODULE_0__["default"]);



/***/ })

}]);