<template>
  <app-card :title="' <b>Prescribed Medicine Requests</b>'" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <td>
          <span v-for="images in row.images" :key="images.image">
            <a :href="images.image" target="_blank">
              <img
                :src="images.image"
                style="width: 50px; height: 50px; border-radius: 50%"
              />
            </a>
          </span>
          <!-- <a v-if="row.images.length > 1" href="#" class="badge">View All Image</a> -->
        </td>
        <td>
          {{ row.user.firstName }} {{ row.user.lastName }} <br />
          <small>{{ row.user.phone }}</small>
          <span class="badge" v-bind:style="{ background: row.dynamicColor }">{{
            row.status
          }}</span>
        </td>
        <!-- <td>
          {{ row.patientName }} <br />
          <small>Age: {{ row.patientAge }} </small>
        </td>
        <td>{{ row.doctorName }} <br> 
        <small>NMC:{{ row.doctorNmc }}</small></td> -->
        <td v-if="row.hospital != null">
          {{ row.hospital != null ? row.hospital.title : "" }} <br />
          <small>Vendors:</small>
          <div v-if="row.hospital.vendors != null">
            <span v-for="vendor in row.hospital.vendors" :key="vendor.id">
              <span class="badge"> {{ vendor.name }} </span>
            </span>
          </div>
        </td>
        <td v-else>-</td>
        <td>
          {{ row.deliveryArea }}<br />
          Rs {{ row.shippingFee }}
        </td>
        <td>
          <a
            v-bind:href="
              '/getLocation?lat=' + row.latitude + '&lang=' + row.longitude
            "
            target="_blank"
            :title="'View on map: ' + row.address"
            >{{ row.address != null ? row.address.substring(0, 40) : "" }}</a
          >
        </td>
        <td>Rs. {{ row.total | commaNumberFormat }}</td>
        <td>
          <span @click="viewText('Additional Detail', row.additionalDetail)">
            {{
              row.additionalDetail != null
                ? row.additionalDetail.substring(0, 40)
                : ""
            }}
          </span>
        </td>
        <td>
          <span @click="viewText('Remarks', row.remarks)">
            {{ row.remarks != null ? row.remarks.substring(0, 40) : "" }}
          </span>
          <a href="#" @click="addRemark(row.id)"
            ><span class="badge"> Add </span></a
          >
        </td>
        <td>
          {{ row.createdAt }}
        </td>
        <td>
          {{
            row.driver != null ? row.driver.name + " " + row.driver.phone : "-"
          }}
        </td>
        <td>
          {{ row.admin != null ? row.admin.name + " " + row.admin.phone : "-" }}
        </td>
        <td width="100">
          <button
            v-if="
              row.status == 'REQUESTED' ||
                row.status == 'PROCESSING' ||
                row.status == 'NOT-FOUND'
            "
            type="button"
            title="Assign Rider"
            class="btn btn-success btn-ajax"
            @click="getRiders(row.id)"
          >
            <i class="material-icons">event_seat</i>
            <div class="ripple-container"></div>
          </button>
          <!-- <button
            v-if="row.status == 'REQUESTED' || row.status == 'PROCESSING' || row.status == 'NOT-FOUND'"
              type="button"
              title="Assign Pharmacist Support"
              class="btn btn-default btn-ajax"
              @click="getSupports(row.id)"
            >
              <i class="material-icons">event_seat</i>
              <div class="ripple-container"></div>
            </button> -->
          <button
            type="button"
            title="Mark as Success"
            class="btn btn-success btn-ajax"
            @click.prevent="updateStatus(row, 'success')"
            :status="row.status"
            v-if="row.status !== 'SUCCESS' && row.status === 'COLLECTED'"
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>
          <button
            title="Active only when delivered and collected."
            type="button"
            class="btn btn-success btn-ajax"
            disabled
            v-else
          >
            <i class="material-icons">done</i>
            <div class="ripple-container"></div>
          </button>
          <button
            type="button"
            title="Mark as Cancelled"
            class="btn btn-danger btn-ajax"
            @click.prevent="updateStatus(row, 'cancelled')"
            :status="row.status"
            v-if="
              row.status !== 'CANCELLED' &&
                row.status !== 'SUCCESS' &&
                row.status !== 'COLLECTED' &&
                row.status !== 'DENIED'
            "
          >
            <i class="material-icons">close</i>
            <div class="ripple-container"></div>
          </button>
          <button type="button" class="btn btn-danger btn-ajax" disabled v-else>
            <i class="material-icons">close</i>
            <div class="ripple-container"></div>
          </button>
          <button
            v-if="row.bills.length > 0"
            class="btn btn-success btn-xs"
            @click="showBillDetail(row)"
          >
            Show Bill
            <div class="ripple-container"></div>
          </button>
        </td>
      </template>
    </app-table-sortable>
    <div class="modal fade" id="assignSupport" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">{{ modelText }}</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="assignPrescriptionForCart">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" v-if="!!supportData.pharmacists">
                    <div class="list-group">
                      <div
                        v-for="(eachPharmacist, index) in supportData
                          .pharmacists.data"
                        :key="index"
                        :value="eachPharmacist.id"
                      >
                        <input
                          type="radio"
                          name="selectrider"
                          :value="eachPharmacist.id"
                          :id="eachPharmacist.id"
                          v-model="supportData.assignedPharmacist"
                        />
                        <label class="list-group-item" :for="eachPharmacist.id"
                          >{{ eachPharmacist.name }}:{{
                            eachPharmacist.phone
                          }}
                          | Junction:
                          <b>{{ eachPharmacist.junction }}</b>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-9 col-md-3">
                  <button
                    v-if="supportData.assignedPharmacist"
                    type="submit"
                    :disabled="errors.any()"
                    class="btn btn-success"
                    style="margin-top: 20%"
                  >
                    Assign
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="billDetail" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Bill Detail</h4>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Bill</th>
                  <th scope="col">Type</th>
                  <th scope="col">Vendor</th>
                  <th scope="col">Bill Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(bill, index) in bills" :key="bill.id">
                  <th scope="row">{{ index + 1 }}</th>
                  <td>
                    <a :href="bill.image" target="_blank"
                      ><img
                        :src="bill.image"
                        style="width: 50px; height: 50px; border-radius: 50%"
                    /></a>
                  </td>
                  <td>{{ bill.type }}</td>
                  <td>
                    {{
                      bill.type == "vendor"
                        ? bill.vendor.businessName
                        : bill.otherVendor
                    }}
                  </td>
                  <td>{{ bill.billAmount }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </app-card>
</template>

<script>
import PrescriptionRequest from "@utils/models/PrescriptionRequest";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "PrescriptionRequestIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: [
        "",
        "User",
        // "Patient",
        // "Doctor",
        "Hospital",
        "Delivery Area",
        "Location",
        "Total",
        "User Additional Detail",
        "Remarks by gogo20",
        "Submitted On",
        "Assigned Driver",
        "Assigned Support",
      ],
      model: new PrescriptionRequest(),
      supportData: {
        pharmacists: null,
        assignedPharmacist: "",
        prescriptionId: "",
      },
      assignType: "",
      modelText: "",
      bills: [],
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    viewText(type, text) {
      swal(type, text);
    },
    showBillDetail(item) {
      this.bills = item.bills;
      $("#billDetail").modal("show");
    },
    updateStatus(row, status) {
      if (confirm("Are you sure? You cannot undo this action.")) {
        axios
          .get(
            this.model.indexUrl +
              "/update-status?formId=" +
              row.id +
              "&status=" +
              status
          )
          .then((response) => {
            if (response.data.message == "success") {
              //   this.rows.data = this.rows.data.filter(
              //     (item) => item.id !== row.id
              //   );
              this.reset();
              alertMessage(
                "Prescription request has been set to " + status + "."
              );
            } else {
              alertMessage("Somethign went wrong.", "danger");
            }
          });
      }
    },
    getSupports(prescriptionId) {
      this.supportData.prescriptionId = prescriptionId;
      axios.get("/admin/get-support/").then((response) => {
        if (response.data != null) {
          this.supportData.pharmacists = response.data;
          this.assignType = "admin";
          this.modelText = "Assign Support for this prescription";
          $("#assignSupport").modal("show");
        } else {
          alertMessage("Unable to retrive pharmacists support", "danger");
        }
      });
    },
    getRiders(prescriptionId) {
      this.supportData.prescriptionId = prescriptionId;
      axios.get("/admin/get-rider/").then((response) => {
        if (response.data != null) {
          this.supportData.pharmacists = response.data;
          this.assignType = "driver";
          this.modelText = "Assign Delivery for this prescription";
          $("#assignSupport").modal("show");
        } else {
          alertMessage("Unable to retrive driver", "danger");
        }
      });
    },
    assignPrescriptionForCart() {
      axios
        .post("/admin/assign-prescription", {
          prescriptionId: this.supportData.prescriptionId,
          adminId: this.supportData.assignedPharmacist,
          type: this.assignType,
        })
        .then((response) => {
          if (response.data.status === true) {
            alertMessage(response.data.message);
            $("#assignSupport").modal("hide");
            this.reset();
          } else {
            alertMessage("Something went wrong while processing", "danger");
          }
        })
        .catch(function(error) {
          alertMessage("Something went wrong while processing", "danger");
        });
    },
    addRemark(no) {
      if (confirm("Are you sure? Changes will reflect in App.")) {
        swal({
          text: "Remark",
          content: "input",
          button: {
            text: "Submit!",
            closeModal: false,
          },
        }).then((remarks) => {
          if (!remarks || remarks.length < 5) {
            return swal("Oh noes!", "Write complete reason!", "error");
          } else {
            axios
              .post("/admin/remark-prescription", {
                prescriptionId: no,
                remarks: remarks,
              })
              .then((response) => {
                if (response.data == "success") {
                  this.reset();
                  alertMessage("Remarks Added");
                } else {
                  if (response.data == false) {
                    alertMessage(
                      "Something Went Wrong. Remarks not added.",
                      "danger"
                    );
                  } else {
                    alertMessage(response.data, "danger");
                  }
                }
              });
            swal.stopLoading();
            swal.close();
          }
        });
      }
    },
    reset() {
      axios.get("/admin/prescription-request").then((response) => {
        this.rows.data = response.data.data;
        this.rows.meta = response.data.meta;
        this.rows.links = response.data.links;
      });
    },
  },
  created() {
    this.getModels();
  },
  mounted() {},
  computed: {
    ...mapGetters(["authUser"]),
  },
  watch: {},
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
.title-right-action1 {
  right: 15px;
  top: 6px;
  float: right;
  margin-left: 10px;
}
.list-group-item {
  user-select: none;
  margin-bottom: 2px;
}

.list-group input[type="checkbox"] {
  display: none;
}

.list-group input[type="checkbox"] + .list-group-item {
  cursor: pointer;
}

.list-group input[type="checkbox"] + .list-group-item:before {
  content: "\2713";
  color: transparent;
  font-weight: bold;
  margin-right: 1em;
}

.list-group input[type="checkbox"]:checked + .list-group-item {
  background-color: #022584;
  color: #fff;
}

.list-group input[type="checkbox"]:checked + .list-group-item:before {
  color: inherit;
}

.list-group input[type="radio"] {
  display: none;
}

.list-group input[type="radio"] + .list-group-item {
  cursor: pointer;
}

.list-group input[type="radio"] + .list-group-item:before {
  content: "\2022";
  color: transparent;
  font-weight: bold;
  margin-right: 1em;
}

.list-group input[type="radio"]:checked + .list-group-item {
  background-color: #0275d8;
  color: #fff;
}

.list-group input[type="radio"]:checked + .list-group-item:before {
  color: inherit;
}
.btn {
  margin-bottom: 3px;
}
</style>
