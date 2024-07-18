(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_models_ProductCategory__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @utils/models/ProductCategory */ "./resources/js/utils/models/ProductCategory.js");
/* harmony import */ var _utils_models_Category__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @utils/models/Category */ "./resources/js/utils/models/Category.js");
/* harmony import */ var _utils_mixins_Crud__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @utils/mixins/Crud */ "./resources/js/utils/mixins/Crud.js");
/* harmony import */ var _CategoryTree__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./CategoryTree */ "./resources/js/components/pages/product-category/CategoryTree.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

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





/* harmony default export */ __webpack_exports__["default"] = ({
  name: "CategoryIndex",
  components: {
    CategoryTree: _CategoryTree__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  mixins: [_utils_mixins_Crud__WEBPACK_IMPORTED_MODULE_3__["index"], _utils_mixins_Crud__WEBPACK_IMPORTED_MODULE_3__["destroy"]],
  data: function data() {
    return {
      columns: ["Image", "Name"],
      rows: {
        data: [],
        links: {},
        meta: {}
      },
      model: new _utils_models_ProductCategory__WEBPACK_IMPORTED_MODULE_1__["default"](),
      file: "",
      type: "category",
      categories: [],
      service: new _utils_models_Category__WEBPACK_IMPORTED_MODULE_2__["default"](),
      services: [],
      active: 0,
      allCount: 0
    };
  },
  methods: _objectSpread(_objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_5__["mapMutations"])(["updateThisMonthCategoriesCount"])), {}, {
    exportSheet: function exportSheet() {
      if (confirm("Are you sure?")) window.location = this.model.indexUrl + "/excel-export";
    },
    submitFile: function submitFile() {
      var formData = new FormData();
      formData.append("import_file", this.file);
      formData.append("type", this.type);
      axios.post(this.model.indexUrl + "/excel-import", formData, {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then(function () {
        alertMessage("Product Category Imported successfully.");
      })["catch"](function () {
        console.log("FAILURE!!");
      });
      this.otherFields();
    },
    otherFields: function otherFields() {
      $("#import").modal("hide");
      this.getModels();
    },
    handleFileUpload: function handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    downloadCategorySample: function downloadCategorySample() {
      var baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Product Category Import.xlsx";
    },
    downloadSubCategorySample: function downloadSubCategorySample() {
      var baseURL = window.location.origin + "/dashboard/excel-samples/";
      location.href = baseURL + "Product Sub Category Import.xlsx";
    },
    reset: function reset() {
      var _this = this;

      axios.get("/admin/product-category").then(function (response) {
        _this.active = 0;
        _this.rows.data = response.data.data;
      });
    },
    fetchCategoryData: function fetchCategoryData(id) {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var data;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return _this2.model.getData(id);

              case 2:
                data = _context.sent;
                _this2.rows.data = data.data;
                _this2.active = id;

              case 5:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    getCategories: function getCategories() {
      var _this3 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
        var categories;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _context2.next = 2;
                return _this3.model.getRoot();

              case 2:
                categories = _context2.sent;
                _this3.categories = categories.data.map(function (category) {
                  return {
                    id: category.id,
                    name: category.name,
                    slug: category.slug
                  };
                });

              case 4:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    getServices: function getServices() {
      var _this4 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3() {
        var services;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _context3.next = 2;
                return _this4.service.getAll();

              case 2:
                services = _context3.sent;
                _this4.services = services.data.map(function (item) {
                  _this4.allCount += item.categoryCount;
                  return {
                    id: item.id,
                    name: item.name,
                    categoryCount: item.categoryCount
                  };
                });

              case 4:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    }
  }),
  mounted: function mounted() {
    this.getModels();
    this.getCategories();
    this.getServices();
  },
  created: function created() {
    if (this.$route.params.active && this.$route.params.active !== 0) {
      this.fetchCategoryData(this.$route.params.active);
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a& ***!
  \******************************************************************************************************************************************************************************************************************************/
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
    "app-card",
    { attrs: { title: "All <b>Product Categories</b>", "body-padding": "0" } },
    [
      _c(
        "template",
        { slot: "actions" },
        [
          _c(
            "app-btn-link",
            { attrs: { "route-name": "product-category.create" } },
            [_vm._v("Add New")]
          ),
          _vm._v(" "),
          _c(
            "app-btn",
            {
              attrs: { background: "info", icon: "archive" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.exportSheet($event)
                }
              }
            },
            [_vm._v("Download Excel")]
          ),
          _vm._v(" "),
          _c(
            "app-btn",
            {
              attrs: {
                background: "warning",
                icon: "cloud_upload",
                "data-toggle": "modal",
                "data-target": "#import"
              }
            },
            [_vm._v("Import Excel\n    ")]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "ul",
        {
          staticClass: "nav nav-pills nav-pills-warning",
          staticStyle: { padding: "5px" }
        },
        [
          _c(
            "li",
            {
              class: _vm.active == 0 ? "active" : "",
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.reset($event)
                }
              }
            },
            [
              _c(
                "a",
                {
                  attrs: {
                    href: "#all",
                    "data-toggle": "tab",
                    "aria-expanded": "true"
                  }
                },
                [
                  _vm._v("All "),
                  _c("span", { staticClass: "badge" }, [
                    _vm._v(_vm._s(_vm.allCount))
                  ])
                ]
              )
            ]
          ),
          _vm._v(" "),
          _vm._l(_vm.services, function(category, index) {
            return _c(
              "li",
              {
                key: index,
                class: _vm.active == category.id ? "active" : "",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.fetchCategoryData(category.id)
                  }
                }
              },
              [
                _c(
                  "a",
                  {
                    attrs: {
                      href: "#" + category.slug,
                      "data-toggle": "tab",
                      "aria-expanded": "true"
                    }
                  },
                  [
                    _vm._v(_vm._s(category.name) + "\n        "),
                    _c("sup", [
                      _c("span", { staticClass: "badge" }, [
                        _vm._v(_vm._s(category.categoryCount))
                      ])
                    ])
                  ]
                )
              ]
            )
          })
        ],
        2
      ),
      _vm._v(" "),
      _c("category-tree", {
        staticStyle: { padding: "0" },
        attrs: { items: _vm.rows.data, tab: _vm.active },
        on: { deleteItem: _vm.deleteModel }
      }),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "modal fade", attrs: { id: "import", role: "dialog" } },
        [
          _c("div", { staticClass: "modal-dialog" }, [
            _c("div", { staticClass: "modal-content" }, [
              _c("div", { staticClass: "modal-header" }, [
                _c(
                  "button",
                  {
                    staticClass: "close",
                    attrs: { type: "button", "data-dismiss": "modal" }
                  },
                  [_vm._v("\n            ×\n          ")]
                ),
                _vm._v(" "),
                _c("h4", { staticClass: "modal-title" }, [
                  _vm._v("Import Category Excel")
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "modal-body" }, [
                _c("input", {
                  ref: "file",
                  staticClass: "btn btn-secondary",
                  attrs: { type: "file", id: "file", name: "import_file" },
                  on: {
                    change: function($event) {
                      return _vm.handleFileUpload()
                    }
                  }
                }),
                _vm._v(" "),
                _c(
                  "select",
                  {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.type,
                        expression: "type"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { name: "type" },
                    on: {
                      change: function($event) {
                        var $$selectedVal = Array.prototype.filter
                          .call($event.target.options, function(o) {
                            return o.selected
                          })
                          .map(function(o) {
                            var val = "_value" in o ? o._value : o.value
                            return val
                          })
                        _vm.type = $event.target.multiple
                          ? $$selectedVal
                          : $$selectedVal[0]
                      }
                    }
                  },
                  [
                    _c("option", { attrs: { disabled: "", selected: "" } }, [
                      _vm._v("Selct Type of Data")
                    ]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "category" } }, [
                      _vm._v("Category")
                    ]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "subcategory" } }, [
                      _vm._v("Sub Category")
                    ])
                  ]
                ),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn btn-round btn-primary",
                    attrs: { type: "submit" },
                    on: {
                      click: function($event) {
                        return _vm.submitFile()
                      }
                    }
                  },
                  [_vm._v("\n            Import\n          ")]
                ),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn btn-sm btn-round btn-warning",
                    attrs: { type: "button" },
                    on: {
                      click: function($event) {
                        return _vm.downloadCategorySample()
                      }
                    }
                  },
                  [_vm._v("\n            Download Category Sample\n          ")]
                ),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn btn-sm btn-round btn-warning",
                    attrs: { type: "button" },
                    on: {
                      click: function($event) {
                        return _vm.downloadSubCategorySample()
                      }
                    }
                  },
                  [
                    _vm._v(
                      "\n            Download Sub Category Sample\n          "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "modal-footer" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-default",
                    attrs: { type: "button", "data-dismiss": "modal" }
                  },
                  [_vm._v("\n            Close\n          ")]
                )
              ])
            ])
          ])
        ]
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/pages/product-category/List.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/components/pages/product-category/List.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=63b4566a& */ "./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pages/product-category/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/product-category/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a&":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=63b4566a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/product-category/List.vue?vue&type=template&id=63b4566a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_63b4566a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);