<template>
  <app-card
    :title="edit ? 'Edit ' + form.businessName : 'Add New <b>Vendor</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="col-md-12">
        <ul class="nav nav-pills nav-pills-warning">
          <li class="active">
            <a href="#detail" data-toggle="tab" aria-expanded="true">Detail</a>
          </li>
          <li class>
            <a href="#options" data-toggle="tab" aria-expanded="false"
              >Options</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="detail">
            <br />

            <div class="row">
              <div class="col-md-offset-3 col-md-6">
                <input-image
                  v-model="form.image"
                  :image-url="imageUrl"
                  label="Image"
                  name="image"
                  :error-text="errors.first('image')"
                  id="image"
                  width="150"
                  height="150"
                ></input-image>
                <small class="text-danger" v-if="form.errors.has('image')"
                  >* {{ form.errors.get("image") }}
                </small>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <input-text
                  label="Business Name"
                  name="business_name"
                  v-validate="'required'"
                  v-model="form.businessName"
                  required
                  :error-text="errors.first('business_name')"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="First Name"
                  name="first_name"
                  v-validate="'required'"
                  v-model="form.firstName"
                  required
                  :error-text="errors.first('first_name')"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="Last Name"
                  name="last_name"
                  v-validate="'required'"
                  v-model="form.lastName"
                  required
                  :error-text="errors.first('last_name')"
                ></input-text>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <input-text
                  label="Phone"
                  name="phone"
                  type="number"
                  v-model="form.phone"
                  :error-text="errors.first('phone')"
                ></input-text>
                <small
                  class="text-center text-danger"
                  v-if="form.errors.has('phone')"
                  >* {{ form.errors.get("phone") }}</small
                >
              </div>
              <div class="col-md-4">
                <input-text
                  label="E-mail"
                  name="email"
                  v-validate="'email'"
                  v-model="form.email"
                  :error-text="errors.first('email')"
                ></input-text>
                <small
                  class="text-center text-danger"
                  v-if="form.errors.has('email')"
                  >* {{ form.errors.get("email") }}</small
                >
              </div>
              <div class="col-md-4">
                <div class="form-group asdh-select" name="role">
                  <label>Settlement Mode</label>
                  <select class="form-control" v-model="form.settlementTime">
                    <option value disabled>Select mode</option>
                    <option
                      data-tokens=""
                      :value="option.id"
                      v-for="(option, index) in tos"
                      :key="index"
                    >
                      {{ option.name.toUpperCase() }}
                    </option>
                  </select>
                </div>
                <small
                  class="text-center text-danger"
                  v-if="form.errors.has('settlement_time')"
                  >* {{ form.errors.get("settlement_time") }}</small
                >
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <input-text
                  label="City"
                  name="city"
                  v-model="form.city"
                  :error-text="errors.first('city')"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="Address"
                  name="address"
                  v-model="form.address"
                  :error-text="errors.first('address')"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="Area"
                  name="area"
                  v-model="form.area"
                  :error-text="errors.first('area')"
                ></input-text>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <input-text
                  label="Exact Latitude"
                  name="lat"
                  v-model="form.lat"
                  :error-text="errors.first('lat')"
                  autocomplete="off"
                ></input-text>
              </div>
              <div class="col-md-4">
                <input-text
                  label="Exact Longitude"
                  name="long"
                  v-model="form.long"
                  :error-text="errors.first('long')"
                  autocomplete="off"
                ></input-text>
              </div>

              <div class="col-md-4">
                <input-text
                  label="Radius Limit (in KM)"
                  name="radius_limit"
                  v-model="form.radiusLimit"
                  :error-text="errors.first('radius_limit')"
                ></input-text>
              </div>

              <div :class="edit ? 'col-md-12' : 'col-md-6'" v-if="!edit">
                <input-select
                  v-model="form.serviceId"
                  name="service"
                  label="Service"
                  :options="services"
                ></input-select>
              </div>
              <div class="col-md-6" v-if="!edit">
                <input-text
                  label="Password"
                  type="password"
                  name="password"
                  v-model="form.password"
                  v-validate="'required'"
                  :error-text="errors.first('password')"
                  required
                ></input-text>
                <small
                  class="text-center text-danger"
                  v-if="form.errors.has('password')"
                  >* {{ form.errors.get("password") }}</small
                >
              </div>
              <div class="col-md-2">
                <input-checkbox
                  label="Hide Vendor"
                  v-model="form.isHidden"
                ></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox
                  label="Takeaway Service"
                  v-model="form.takeaway"
                ></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox
                  label="Dine In Service"
                  v-model="form.dineIn"
                ></input-checkbox>
              </div>
            </div>

            <div class="row">
              <div class="col-md-offset-1 col-md-10">
                <center><h4>Opening Hours</h4></center>
                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">SN.</th>
                      <th scope="col">Day</th>
                      <th scope="col">Opens Time</th>
                      <th scope="col">Closes Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(time, index) in form.openingTimeForm"
                      :key="index"
                    >
                      <th scope="row">
                        <input-checkbox v-model="time.status"></input-checkbox>
                      </th>
                      <td>{{ days[index] }}</td>
                      <td>
                        <input-text
                          v-model="time.opentime"
                          label="Opening Time"
                          :name="'opentime' + index"
                          type="text"
                          placeholder="HH::MM (Eg. 10:30)"
                        ></input-text>
                      </td>
                      <td>
                        <input-text
                          label="Closing Time"
                          v-model="time.closetime"
                          :name="'closetime' + index"
                          type="text"
                          placeholder="HH::MM (Eg. 15:30)"
                        ></input-text>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="options">
            <div class="row">
              <div
                class="col-md-2"
                v-for="item in form.vendorOptionCategories"
                :key="item.id"
              >
                <input-checkbox
                  :label="item.name"
                  v-model="item.status"
                ></input-checkbox>
                <span class="badge">{{ item.status === true }}</span>
              </div>
            </div>
            <br />
            <div class="row" style="background: #e1e1e1; border-radius: 20px">
              <center><span class="text-muted">Advance Control</span></center>
              <br />
              <div class="col-md-2">
                <input-checkbox
                  label="Hide"
                  v-model="form.isHidden"
                ></input-checkbox>
              </div>
              <div class="col-md-2">
                <input-checkbox
                  label="Active Status"
                  v-model="form.status"
                ></input-checkbox>
              </div>
              <div class="col-md-4">
                <input-checkbox
                  label="Order Offer Applicable"
                  v-model="form.orderOfferApplicable"
                ></input-checkbox>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-right">
        <button type="submit" :disabled="errors.any()" class="btn btn-success">
          {{ edit ? "Update" : "Save" }}
        </button>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Vendor from "@utils/models/Vendor";
import Service from "@utils/models/Category";

import { store, save } from "@utils/mixins/Crud";

export default {
  name: "VendorCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      vendorId: "",
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        image: "",
        firstName: "",
        lastName: "",
        businessName: "",
        countryCode: "+977",
        phone: "",
        email: "",
        type: "",
        city: "",
        address: "",
        area: "",
        lat: "",
        long: "",
        verified: 1,
        password: "",
        serviceId: "",
        isHidden: false,
        status: true,
        orderOfferApplicable: true,
        settlementTime: 30,
        radiusLimit: "",
        openingTimeForm: [],
        vendorOptionCategories: [],
        takeaway: false,
        dineIn: false,
      }),
      vendorOptionCategories: [],
      model: new Vendor(),
      service: new Service(),
      services: [],
      days: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
      ],
      tos: [
        {
          id: "7",
          name: "Weekly",
        },
        {
          id: "15",
          name: "Semi-Monthly",
        },
        {
          id: "30",
          name: "Monthly",
        },
      ],
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();
      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `vendor.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");

        this.$router.push({
          name: `vendor.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        id,
        firstName,
        lastName,
        businessName,
        phone,
        email,
        city,
        address,
        area,
        lat,
        long,
        settlementTime,
        vendorOptionCategories,
        hide,
        enable,
        orderOfferApplicable,
        radiusLimit,
        image50,
        takeaway,
        dineIn,
      } = data.data;

      this.form = new Form({
        firstName,
        lastName,
        businessName,
        countryCode: "+977",
        phone,
        email,
        image: "",
        city,
        address,
        area,
        lat,
        long,
        isHidden: hide,
        status: enable,
        orderOfferApplicable: orderOfferApplicable,
        openingTimeForm: [],
        settlementTime,
        vendorOptionCategories,
        radiusLimit,
        takeaway,
        dineIn,
      });

      this.imageUrl = image50;

      this.vendorId = id;

      this.tos = [
        {
          id: "7",
          name: "Weekly",
        },
        {
          id: "15",
          name: "Semi-Monthly",
        },
        {
          id: "30",
          name: "Monthly",
        },
      ];

      if (vendorOptionCategories.length === 0) {
        this.form.vendorOptionCategories = this.vendorOptionCategories;
      } else {
        this.form.vendorOptionCategories = vendorOptionCategories[0].map(
          (optionCategory) => {
            return {
              id: optionCategory.id,
              name: optionCategory.title,
              status: optionCategory.status,
            };
          }
        );
      }

      this.getOpenCloseSchedule();
    },

    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        return {
          id: item.id,
          name: item.name,
        };
      });
    },
    getOpenCloseSchedule() {
      axios
        .get("/admin/vendor/open-close-time/" + this.$route.params.id)
        .then((response) => {
          this.form.openingTimeForm = response.data;
        });
    },
    clearError() {
      this.form.errors.errors = {};
    },
    async getVendorOptions(vendor = "", service = "") {
      let vendorOptionCategories = await this.model.getVendorOptions(
        vendor,
        service
      );
      this.vendorOptionCategories = vendorOptionCategories.data.map((item) => {
        return {
          id: item.id,
          name: item.title,
          status: false,
        };
      });
      if (!this.edit) {
        this.form.vendorOptionCategories = vendorOptionCategories.data.map(
          (optionCategory) => {
            return {
              id: optionCategory.id,
              name: optionCategory.title,
              status: false,
            };
          }
        );
      }
    },
    vendorOptionCategoriesUnion() {
      const optionList = this.form.vendorOptionCategories.concat(
        this.vendorOptionCategories
      );

      let set = new Set();
      let unionArray = optionList.filter((item) => {
        if (!set.has(item.id)) {
          set.add(item.id);
          return true;
        }
        return false;
      }, set);

      this.form.vendorOptionCategories = unionArray;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getServices();

    if (this.edit) {
      this.getData();
      this.imageUrl = Helpers.loadingImage();
    } else {
      this.form.openingTimeForm = [
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
        {
          opentime: null,
          closetime: null,
          status: false,
        },
      ];
    }
  },

  watch: {
    "form.serviceId": function (val) {
      if (!this.edit) {
        this.getVendorOptions("", val);
      }
    },
    vendorId: function (val) {
      this.getVendorOptions(val, "");
    },
    vendorOptionCategories: function (val) {
      this.vendorOptionCategoriesUnion();
    },
    "form.image": function (val) {
      let type = typeof val;
      if (type === "object") {
        this.form.errors.clear("image");
      }
    },
  },
};
</script>