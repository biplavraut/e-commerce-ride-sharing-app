<template>
  <div>
    <app-card title="All <b>Vendors</b>" body-padding="0">
      <template slot="actions">
        <app-btn-link route-name="vendor.create">Add New</app-btn-link>
      </template>

      <ul class="nav nav-pills nav-pills-warning" style="padding: 5px" v-if="this.authUser.type ==='admin' || this.authUser.type ==='superadmin'">
        <li class="active" @click.prevent="allData">
          <a href="#all" data-toggle="tab" aria-expanded="true"
            >All <span class="badge">{{ allCount }}</span></a
          >
        </li>
        <li
          v-for="(category, index) in services"
          :key="index"
          @click.prevent="fetchCategoryData(category.id)"
        >
          <a :href="'#' + category.slug" data-toggle="tab" aria-expanded="true"
            >{{ category.name }}
            <sup>
              <span class="badge">{{ category.count }}</span>
            </sup>
          </a>
        </li>
        <li style="display: flex;" class="pull-right">
          <input-checkbox label="Hidden Only" @change="reset" v-model="hidden"></input-checkbox>&nbsp; &nbsp;
          <input-checkbox label="Inactive Only" @change="reset" v-model="isInActive"></input-checkbox>&nbsp;
        </li>
      </ul>

      <app-table-sortable
        :columns="columns"
        :rows="rows"
        :searchUrl="'/admin/vendor/get-vendors?name='"
      >
        <template slot-scope="{ row }">
          <td>
            <span
              class="badge"
              title="Registered From"
              :style="
                row.from === 'app'
                  ? 'background-color: blue'
                  : 'background-color: green'
              "
              >{{ row.from }}</span
            ><br />
            <span
              @click="copy(row.vendorId)"
              style="cursor: pointer"
              title="click to copy"
              class="badge"
              >{{ row.vendorId }}</span
            >
            <span
              ><button
                class="btn btn-sm btn-primary"
                @click="addMoreFund(row.id, row.businessName)"
              >
                Add Adv. Fund
              </button></span
            ><br />
            <span
              v-if="row.hide"
              class="badge alert-primary"
              style="background: #de181890"
              >hidden</span
            >
            <span v-if="!row.enable" class="badge alert-primary"
              style="background: #de1818">
              Inactive
            </span>
          </td>
          <td width="100">
            <a :href="row.image" target="_blank">
            <img
              :src="row.image50"
              style="width: 50px; height: 50px; border-radius: 50%"
            />
            </a>
          </td>

          <td>
            {{ row.businessName }}
            <br />
            <span
              style="cursor: none"
              title="Verified"
              class="btn btn-success btn-ajax"
              v-if="row.verified"
              ><i class="material-icons">verified_user</i></span
            >
          </td>
          <td>{{ row.firstName }} {{ row.lastName }}</td>
          <td>
            {{ row.email ? row.email : "-" }}
            <small v-if="!row.emailVerified" title="Not Verified">
              <span>
                <i class="material-icons">close</i>
              </span>
            </small>
            <small v-else title="Verified">
              <span>
                <i class="material-icons">done</i>
              </span>
            </small>
            <br />
            {{ row.phone }}
            <small v-if="!row.phoneVerified" title="Not Verified">
              <span>
                <i class="material-icons">close</i>
              </span>
            </small>
            <small v-else title="Verified">
              <span>
                <i class="material-icons">done</i>
              </span>
            </small>
          </td>
          <td :title="row.address"  @click="showAddress(row.address)" class="cursor">
            {{ row.address ? row.address.substring(0, 10) + "..." : "-" }}
          </td>
          <td>
            <span
              class="badge"
              v-for="(service, index) in row.serviceList"
              :key="index"
              >{{ service }}</span
            >
          </td>

          <td title="Total Products">
            <router-link
              :to="{
                name: 'product.index',
                params: { phone: row.phone },
              }"
              title="Click to go"
            >
              <span class="badge cursor">{{ row.products }}</span>
            </router-link>
          </td>

          <td>{{ formatDate(row.createdAt) }}</td>

          <td
            v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
          >
            <div class="col-md-3" v-if="!row.verified">
              <button
                type="button"
                title="Verify Now"
                class="btn btn-warning btn-ajax"
                data-toggle="modal"
                data-target="#info"
                @click="chooseService(row)"
              >
                <i class="material-icons">done</i>
              </button>
            </div>

            <div class="col-md-3" v-if="row.verified">
              <app-actions
                :actions="{
                  edit: { name: 'vendor.edit', params: { id: row.id } },
                }"
              ></app-actions>
            </div>

            <div class="col-md-9">
              <div :class="row.verified ? 'col-md-4' : 'col-md-9'">
                <app-actions
                  @deleteItem="deleteModel"
                  :actions="{
                    delete: row.id,
                  }"
                ></app-actions>
              </div>

              <div class="col-md-4" v-if="row.verified">
                <button
                  @click="disableVendor(row.id)"
                  title="Enable/Disable vendor with product"
                  class="btn"
                  :class="
                    row.enable
                      ? 'btn-danger btn-ajax btn-link'
                      : 'btn-default btn-ajax btn-link'
                  "
                >
                  <i class="fa fa-ban"></i>
                </button>
              </div>

              <div class="col-md-4" v-if="row.verified">
                <button
                  @click="hideVendor(row.id)"
                  title="Hide/Unhide"
                  class="btn"
                  :class="
                    row.hide
                      ? 'btn-success btn-ajax btn-link'
                      : 'btn-warning btn-ajax btn-link'
                  "
                >
                  <i class="material-icons">
                    {{ row.hide ? "visibility" : "visibility_off" }}
                  </i>
                </button>
              </div>
            </div>
          </td>
          <td v-else>-</td>
        </template>
      </app-table-sortable>

      <!-- Modal -->
      <div class="modal fade" id="info" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                &times;
              </button>
              <h4 class="modal-title">Choose Services for this vendor</h4>
            </div>
            <div class="modal-body">
              <form @submit.prevent="verifyNow">
                <div class="row">
                  <div class="col-md-12">
                    <div
                      class="col-md-4"
                      v-for="(service, index) in services"
                      :key="index"
                    >
                      <!-- <input-checkbox
                        @change="serviceChange(service.id)"
                        :label="service.name"
                        :checked="selectedService(service.slug)"
                      ></input-checkbox> -->
                      <label>
                        <input
                          type="checkbox"
                          name="checkbox"
                          @change="serviceChange(service.id)"
                          :checked="selectedService(service.slug)"
                        />
                        {{ service.name }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group asdh-select" name="role">
                      <label>Settlement Mode</label>
                      <select class="form-control" v-model="settlement">
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
                  </div>
                  <div class="col-md-12">
                    <button
                      type="submit"
                      :disabled="errors.any()"
                      class="pull-right btn btn-success"
                    >
                      Verify
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </app-card>

    <!-- Start of Input Modal  -->
    <div
      class="modal fade"
      id="addNewModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewModelModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              id="modelClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="addNewModelModalLabel"></h5>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="vendor_title" class="col-form-label">Vendor*</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.vendor_business_name"
                  id="vendor_business_name"
                  required
                />
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="amount" class="col-form-label">Amount*</label>
                    <input
                      type="number"
                      v-model="form.amount"
                      id="amount"
                      class="form-control"
                      min="0"
                      required
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="remarks" class="col-form-label">Remarks*</label>
                    <input
                      type="text"
                      v-model="form.remarks"
                      class="form-control"
                      id="remarks"
                      required
                    />
                  </div>
                </div>
              </div>
              <input
                type="hidden"
                v-model="form.vendor_id"
                id="vendor-id"
                name="vendor_id"
                value=""
              />
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancle
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="addFundData()"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of input Model -->
  </div>
</template>

<script>
import Form from "@utils/Form";
import Vendor from "@utils/models/Vendor";
import Service from "@utils/models/Category";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import Checkbox from '../../material/input/Checkbox.vue';

export default {
  components: { Checkbox },
  name: "VendorIndex",

  mixins: [index, destroy],
  data() {
    return {
      columns: [
        "#",
        "Logo",
        "Business",
        "Full Name",
        "Contact",
        "Address",
        "Services",
        "Products",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Vendor(),
      service: new Service(),
      serviceId: [],
      settlement: "30",
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
      vendor: "",
      services: [],
      allCount: 0,
      form: new Form({
        vendor_id: "",
        vendor_business_name: "",
        amount: "",
        remarks: "",
      }),
      addfund: false,
      catData : "all",
      hidden: false,
      isInActive: false
    };
  },

  methods: {
    serviceChange(service) {
      var exist = this.serviceId.includes(service);
      if (!exist) {
        this.serviceId.push(service);
      } else {
        const index = this.serviceId.indexOf(service);
        if (index > -1) {
          this.serviceId.splice(index, 1);
        }
      }
    },
    selectedService(slug){
      var serviceSlug = this.vendor.serviceSlug ?? []
      var desireServiceType = this.vendor.type ?? ''
      if(serviceSlug.indexOf(slug) >= 0 || desireServiceType == slug){
        return true;
      }
    },
    exportSheet() {
      if (confirm("Are you sure?"))
        window.location = this.model.indexUrl + "/excel-export";
    },

    hideVendor(id) {
      var response = confirm("Are you sure want to process this action");
      if (response) {
        axios.get("/admin/vendor/action/" + id).then((response) => {
          alertMessage("Action performed successfully");
          this.reset();
          // location.reload();
        });
      }
    },

    disableVendor(id) {
      var response = confirm("Are you sure want to process this action");
      if (response) {
        axios.get("/admin/vendor/disable/" + id).then((response) => {
          alertMessage("Action performed successfully");
          this.reset();
          // location.reload();
        });
      }
    },

    formatDate(date) {
      return moment(date).format("LLLL");
    },
    chooseService(row) {
      this.vendor = row;
    },
    async verifyNow() {
      if (this.serviceId.length > 0) {
        let response = await this.model.verify(
          this.vendor.id,
          this.serviceId,
          this.settlement
        );
        if (response === "success") {
          this.vendor.verified = true;
          alertMessage("Vendor Verified");
          $("#info").modal().click();
          this.reset();
        } else {
          alertMessage(response, "danger");
          $("#info").modal().click();
        }
      } else {
        alertMessage("Please select service(s) to verify.", "danger");
      }
    },
    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        return {
          id: item.id,
          name: item.name,
          slug: item.slug,
          count: item.count,
        };
      });
    },
    allData(){
      this.catData = "all"
      this.reset();
    },
    reset() {
      if(this.authUser.type ==='admin' || this.authUser.type ==='superadmin'){
          if(this.catData == "all"){
            axios.get("/admin/vendor?isHidden=" + this.hidden + "&isInActive=" + this.isInActive).then((response) => {
            this.rows = { data: [], links: {}, meta: {} };
            this.rows.data = response.data.data;
            this.rows.links = response.data.links;
            this.rows.meta = response.data.meta;
            });
          }else{
            this.fetchCategoryData(this.catData);
          }
        
      }
    },
    async fetchCategoryData(id) {
      this.catData = id
      let data = await this.model.getData(id, this.hidden, this.isInActive);
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = data.data;
      this.rows.links = data.links;
      this.rows.meta = data.meta;
    },
    copy(no) {
      copyContent(no);
    },

    // Adding Fund To vendor
    addMoreFund(vendor_id, vendor_business_name) {
      this.addfund = true;
      this.form.reset();
      this.form.vendor_id = vendor_id;
      this.form.vendor_business_name = vendor_business_name;
      var modal = $("#addNewModel").modal();
      var text = "Add Advance Settlement Fund";
      modal.find(".modal-title").text(text);
      modal.find(".modal-body #amount").attr("disabled", false);
      modal
        .find(".modal-body #vendor_business_name")
        .attr("disabled", "disabled");
      modal.show();
    },
    addFundData() {
      swal({
        title: "Are you sure?",
        text: "Once Added, you will not be able to revert the amount!",
        icon: "warning",
        buttons: ["Cancle!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
        if (result) {
          if (!this.form.amount || !this.form.vendor_id || !this.form.remarks) {
            swal("Invalid Input");
            throw null;
          } else {
            $("#modelClose").click();
            this.form
              .post("/admin/vendor-advance-settlement/add")
              .then((response) => {
                if (response.status) {
                  alertMessage(response.message);
                  this.form.reset();
                  // reload or redirect
                } else {
                  alertMessage("Action cannot be processed.", "danger");
                }
              });
            swal.stopLoading();
            swal.close();
          }
        }
      });
    },
    showAddress(address) {
      if (address) {
        swal("Full Address", address);
      }
    },
  },

  mounted() {
    if(this.authUser.type ==='admin' || this.authUser.type ==='superadmin'){
      this.getModels();
      this.getServices();
    }

    setTimeout(() => {
      this.allCount = this.rows.meta.total;
    }, 2000);
  },
  watch: {},
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>
