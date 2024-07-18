(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[16],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/Tor.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/Tor.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _routes_admin__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @routes/admin */ "./resources/js/routes/admin.js");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _utils_Form__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @utils/Form */ "./resources/js/utils/Form.js");
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



/* harmony default export */ __webpack_exports__["default"] = ({
  name: "Tor",
  data: function data() {
    return {
      form: new _utils_Form__WEBPACK_IMPORTED_MODULE_2__["default"]({
        name: "",
        email: "",
        phone: "",
        establishedDate: "",
        address: "",
        about: "",
        riderTac: "",
        userTac: "",
        vendorTac: "",
        logo: ""
      }),
      logoUrl: "/images/camera.png"
    };
  },
  methods: {
    updateSettings: function updateSettings() {
      var _this = this;

      this.$validator.validate().then(function (result) {
        if (result) {
          _this.form.put(_routes_admin__WEBPACK_IMPORTED_MODULE_0__["UPDATE_SETTINGS_URL"]).then(function (data) {
            _this.$store.commit("setSettings", data.data);

            _this.logoUrl = _this.settings.logo;
            _this.form.logo = "";
            alertMessage("TOR successfully saved.");
          })["catch"](function (error) {
            switch (error.status) {
              case 422:
                _this.form.errors.initialize(error.data.errors);

                if (_this.form.errors.has("logo")) Helpers.focusId("settings-logo");
                break;

              default:
                alertMessage(error.data.message, "danger");
                break;
            }
          });
        } else {
          Helpers.focusFirstError(_this.errors);
        }
      });
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapGetters"])(["settings"])),
  mounted: function mounted() {
    this.form = new _utils_Form__WEBPACK_IMPORTED_MODULE_2__["default"](this.settings);
    this.form.logo = "";
    this.logoUrl = this.settings.logo;
  },
  watch: {
    "form.logo": function formLogo(val) {
      this.form.errors.clear("logo");
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1& ***!
  \************************************************************************************************************************************************************************************************************/
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
    "form",
    {
      on: {
        submit: function($event) {
          $event.preventDefault()
          return _vm.updateSettings($event)
        }
      }
    },
    [
      _c("app-card", { attrs: { title: "Edit <b>TOR</b>" } }, [
        _c("div", { staticClass: "row" }, [
          _c(
            "div",
            { staticClass: "col-md-12" },
            [
              _c("app-quill-editor", {
                attrs: {
                  label: "User's Terms & Condition",
                  name: "user_tac",
                  "error-text": _vm.errors.first("user_tac")
                },
                model: {
                  value: _vm.form.userTac,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "userTac", $$v)
                  },
                  expression: "form.userTac"
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-12" },
            [
              _c("app-quill-editor", {
                attrs: {
                  label: "Rider's Terms & Condition",
                  name: "rider_tac",
                  "error-text": _vm.errors.first("rider_tac")
                },
                model: {
                  value: _vm.form.riderTac,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "riderTac", $$v)
                  },
                  expression: "form.riderTac"
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-12" },
            [
              _c("app-quill-editor", {
                attrs: {
                  label: "Vendor's Terms & Condition",
                  name: "vendor_tac",
                  "error-text": _vm.errors.first("vendor_tac")
                },
                model: {
                  value: _vm.form.vendorTac,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "vendorTac", $$v)
                  },
                  expression: "form.vendorTac"
                }
              })
            ],
            1
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "text-right" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-success",
              attrs: {
                type: "submit",
                disabled: _vm.form.errors.any(_vm.errors.any())
              }
            },
            [_vm._v("\n        Save\n      ")]
          )
        ])
      ])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/pages/Tor.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/pages/Tor.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Tor.vue?vue&type=template&id=573478b1& */ "./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1&");
/* harmony import */ var _Tor_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Tor.vue?vue&type=script&lang=js& */ "./resources/js/components/pages/Tor.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Tor_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pages/Tor.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pages/Tor.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/components/pages/Tor.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Tor_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Tor.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/Tor.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Tor_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Tor.vue?vue&type=template&id=573478b1& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/Tor.vue?vue&type=template&id=573478b1&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tor_vue_vue_type_template_id_573478b1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);