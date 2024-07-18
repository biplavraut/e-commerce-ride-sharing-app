<template>
  <app-card :title="title" body-padding="0">
    <template slot="actions" v-if="currentRider">
      <a
        href="#"
        class="btn btn-round btn-xs title-right-action1 btn-success"
        @click="reset"
        title="Go Back"
      >
        <i class="material-icons">reply</i></a
      >
    </template>

    <div v-if="!currentRider">
      <button
        :class="
          active === 'reset'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="reset"
        title="Refresh"
      >
        <i class="material-icons">person_off</i>
        Unverified Rider
        <sup>
          <span class="badge" v-if="counts.unverified > 0">{{
            counts.unverified
          }}</span>
        </sup>
      </button>
      <span v-if="this.authUser.type ==='admin' || this.authUser.type ==='superadmin'">
        <button
        :class="
          active === 'verified'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getVerifiedRider"
        title="Verified Only"
      >
        <i class="material-icons">verified_user</i>
        Verified Rider
        <sup>
          <span class="badge" v-if="counts.verified > 0">{{
            counts.verified
          }}</span>
        </sup>
      </button>
      <button
        :class="
          active === 'blocked'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getBlockedRider"
        title="Blocked Only"
      >
        <i class="material-icons">block</i>
        Blocked Rider
        <sup>
          <span class="badge" v-if="counts.blocked > 0">{{
            counts.blocked
          }}</span>
        </sup>
      </button>
      <button
        :class="
          active === 'blacklisted'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getBlacklistedRider"
        title="Blacklisted Only"
      >
        <i class="material-icons">cancel</i>
        Blacklisted Rider
        <sup>
          <span class="badge" v-if="counts.blacklisted > 0">{{
            counts.blacklisted
          }}</span>
        </sup>
      </button>
      <button
        :class="
          active === 'active'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getActiveRider"
        title="Active Only"
      >
        <i class="material-icons">info</i>
        Online Rider
        <sup>
          <span class="badge" v-if="counts.active > 0">{{
            counts.active
          }}</span>
        </sup>
      </button>
      <button
        :class="
          active === 'associated'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getAssociatedRider"
        title="Associated Rider Only"
      >
        <i class="material-icons">home</i>
        Associated Rider
        <sup>
          <span class="badge" v-if="counts.associated > 0">{{
            counts.associated
          }}</span>
        </sup>
      </button>
      <button
        :class="
          active === 'incomplete'
            ? 'btn btn-round btn-success btn-xs title-right-action'
            : 'btn btn-round btn-xs title-right-action'
        "
        @click="getIncompleteRider"
        title="Rider's whose document is incomplete"
      >
        <i class="material-icons">rule</i>
        Incomplete Document
        <sup>
          <span class="badge" v-if="counts.incomplete > 0">{{
            counts.incomplete
          }}</span>
        </sup>
      </button>      
      </span>      
    </div>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/delivery-driver/get-drivers?name='"
      v-if="!currentRider"
    >
      <template slot-scope="{ row }" style="background-color: red">
        <td
          width="100"
          @click="locationRider(row.id)"
          :title="
            row.stat === 'active'
              ? 'Active'
              : row.stat === 'ongoing'
              ? 'In a Ride'
              : 'Inactive'
          "
        >
          <span
            class="badge"
            title="Registered From"
            :style="
              row.from === 'app'
                ? 'background-color: blue'
                : 'background-color: green'
            "
            >{{ row.from }}</span
          >
          <a :href="row.image" target="_blank">
          <img
            :src="row.image50"
            :alt="row.stat"
            :style="
              row.stat === 'active'
                ? 'width: 50px;height: 50px;border-radius: 50%;border: 2px inset #4caf50;'
                : row.stat === 'ongoing'
                ? 'width: 50px;height: 50px;border-radius: 50%;    border: 2px inset #ff9800;'
                : 'width: 50px;height: 50px;border-radius: 50%;'
            "
          />
          </a>
        </td>
        <td>
          {{ row.name }} <br />
          <span
            style="cursor: none"
            title="Verified"
            class="btn btn-success btn-ajax"
            v-if="row.verified"
            ><i class="material-icons">verified_user</i></span
          >
        </td>
        <td>
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
        <td>
          {{ row.email }}
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
        </td>

        <td @click="showAddress(row.address)" class="cursor">{{ row.address ? row.address.substring(0, 10) + "..." : "-" }}</td>
        <td>
          {{ row.interestedIn }} <br />
          <small
            class="badge btn-ajax"
            data-toggle="modal"
            data-target="#changeSubscription"
            v-if="row.subscription"
            title="Subscription Type"
            @click="setSubscriptionData(row.id, row.subscription)"
            >{{ row.subscription }}</small
          >
        </td>
        <td>
          Cat : <b>{{ row.licenseCategory }}</b> ||
          <a :href="row.license" title="See License Image" target="_blank">
            <i class="material-icons">image</i>
          </a>
          || No : <b>{{ row.licenseNo }}</b>
          <a
            @click.prevent="updateLicense(row)"
            data-toggle="modal"
            data-target="#info"
            title="Update Expiry Date"
          >
            <i class="material-icons">update</i>
          </a>
        </td>
        <td>
          <a :href="row.blueBook" title="See Bluebook Image" target="_blank">
            <i class="material-icons">image</i>
          </a>
          <a
            @click.prevent="updateBluebook(row)"
            data-toggle="modal"
            data-target="#bluebook"
            title="Update Renew Date"
          >
            <i class="material-icons">update</i>
          </a>
        </td>
        <td>
          Type : <b>{{ row.type }}</b> || Reg No : <b>{{ row.regNumber }}</b> ||
          <a :href="row.picture" title="See Vehicle Image" target="_blank">
            <i class="material-icons">image</i>
          </a>
          || Man. Year : <b>{{ row.manufacturingYear }}</b> || color :
          <b>{{ row.color }}</b>
        </td>

        <td
          @click.prevent="row.usedCode ? copy(row.usedCode) : ''"
          title="Click to copy"
          :style="
            row.usedCode ? 'cursor: pointer; text-decoration: overline' : ''
          "
        >
          {{ row.usedCode ? row.usedCode : "-" }}
        </td>
        <td @click.prevent="row.myReferCode ? copy(row.myReferCode) : ''"
          title="Click to copy">{{row.myReferCode}} - {{ row.referCount }}</td>
        <td>{{ formatDateTime(row.createdAt) }}</td>

        <td>
          <div class="col-md-3">
            <app-actions
              @deleteItem="deleteModel"
              :actions="{
                delete: row.id,
              }"
            ></app-actions>
          </div>

          <div class="col-md-3" v-if="row.verified">
            <button
              type="button"
              :title="row.is_associated ? 'Unassiciate' : 'Associate'"
              class="btn btn-success btn-ajax"
              data-toggle="modal"
              data-target="#junction"
              @click="changeAssociateStatus(row)"
              v-if="!row.is_associated"
            >
              <i class="material-icons">{{
                row.is_associated ? "add_link" : "insert_link"
              }}</i>
            </button>
            <button
              type="button"
              :title="row.is_associated ? 'Unassiciate' : 'Associate'"
              class="btn btn-danger btn-ajax"
              @click="updateAssociation(row.id)"
              v-if="row.is_associated"
            >
              <i class="material-icons">{{
                row.is_associated ? "add_link" : "insert_link"
              }}</i>
            </button>
          </div>

          <div class="col-md-3" v-if="row.verified">
            <button
              type="button"
              title="Expand Row to update Owner's Vehicle Information"
              class="btn btn-success btn-ajax"
              @click.prevent="expandRow(row)"
            >
              <i class="material-icons">expand</i>
              <div class="ripple-container"></div>
            </button>
          </div>

          <div class="col-md-3" v-if="!row.blocked">
            <button
              type="button"
              title="Block Rider"
              class="btn btn-danger btn-ajax"
              @click.prevent="blockRider(row.id)"
            >
              <i class="material-icons">block</i>
              <div class="ripple-container"></div>
            </button>
          </div>

          <div class="col-md-3" v-if="!row.verified">
            <button
              type="button"
              title="Verify Now"
              class="btn btn-warning btn-ajax"
              @click="verifyDriver(row)"
            >
              <i class="material-icons">done</i>
            </button>
          </div>

          <div class="col-md-3" v-if="blockedOnly || row.blocked">
            <button
              type="button"
              title="Unblock Now"
              class="btn btn-danger btn-ajax"
              @click="clearBlock(row)"
            >
              <i class="material-icons">vpn_key</i>
            </button>
          </div>

          <div class="col-md-3" v-if="blacklistedOnly">
            <button
              type="button"
              title="Clear Blacklist Count"
              class="btn btn-warning btn-ajax"
              @click="clearBlacklist(row)"
            >
              <i class="material-icons">layers_clear</i>
              <sup class="badge">{{ row.blacklisted }}</sup>
            </button>
          </div>
        </td>
      </template>
    </app-table-sortable>

    <div v-if="currentRider">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
                License -
                <small class="category">Image</small>
              </h4>
            </div>
            <div class="card-content">
              <small
                class="category pull-right"
                style="z-index: 999"
                v-if="currentRider.licenseExpiry"
                >Expiry Date:
                <b>{{ formatDate(currentRider.licenseExpiry) }}</b> </small
              ><br />

              <img class="zoom" :src="currentRider.license" />

              <br />
              <small class="category">
                License Category: <b>{{ currentRider.licenseCategory }}</b
                ><br />
                License No: <b>{{ currentRider.licenseNo }}</b>
              </small>
              <br />
              <hr />

              <form @submit.prevent="licenseData">
                <input-text
                  label="License Expiry Date"
                  type="date"
                  name="title"
                  v-model="license.expiry"
                  v-validate="'required'"
                  required
                ></input-text>
                <input-image
                  v-model="license.image"
                  :image-url="imageUrl"
                  name="image"
                  label=" License Image"
                  :error-text="errors.first('image')"
                  id="image"
                  width="150"
                  height="150"
                ></input-image>

                <button type="submit" class="btn btn-success pull-right">
                  Update
                </button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
                Bluebook -
                <small class="category">Image</small>
              </h4>
            </div>
            <div class="card-content">
              <small
                class="category pull-right"
                style="z-index: 999"
                v-if="currentRider.bluebookExpiry"
                >Expiry Date:
                <b>{{ formatDate(currentRider.bluebookExpiry) }}</b> </small
              ><br />

              <div class="row">
                <div class="col-md-4">
                  <img
                    class="zoom"
                    :src="currentRider.blueBook"
                    title="Bluebook Showing Vehicle Information"
                  />
                  <input-image
                    v-model="bluebook.first"
                    :image-url="imageUrl"
                    name="image"
                    label=" Vehicle Info Image"
                    :error-text="errors.first('image')"
                    id="image"
                    width="150"
                    height="150"
                  ></input-image>
                </div>
                <div class="col-md-4">
                  <img
                    class="zoom"
                    :src="currentRider.blueBookSec"
                    title="Bluebook Showing Owner Information"
                  />
                  <input-image
                    v-model="bluebook.sec"
                    :image-url="imageUrl"
                    name="image"
                    label=" Owner Info Image"
                    :error-text="errors.first('image')"
                    id="image"
                    width="150"
                    height="150"
                  ></input-image>
                </div>
                <div class="col-md-4">
                  <img
                    class="zoom"
                    :src="currentRider.blueBookTrd"
                    title="Bluebook Showing Latest Tax Information"
                  />
                  <input-image
                    v-model="bluebook.trd"
                    :image-url="imageUrl"
                    name="image"
                    label=" Latest Tax Info Image"
                    :error-text="errors.first('image')"
                    id="image"
                    width="150"
                    height="150"
                  ></input-image>
                </div>

                <div class="col-md-12" style="margin-top: 10%">
                  <input-text
                    label="Bluebook Expiry Date"
                    type="date"
                    name="title"
                    v-model="bluebook.expiry"
                    v-validate="'required'"
                    required
                  ></input-text>

                  <button
                    @click.prevent="bluebookData"
                    class="btn btn-success pull-right"
                  >
                    Update
                  </button>
                </div>
              </div>
              <small class="category">
                Plate No: <b>{{ currentRider.plateNo }}</b
                ><br />
                Reg Number: <b>{{ currentRider.regNumber }}</b
                ><br />
                manufacturing Year: <b>{{ currentRider.manufacturingYear }}</b
                ><br />
                Vehicle Color: <b>{{ currentRider.color }}</b
                ><br />
                License No: <b>{{ currentRider.licenseNo }}</b>
              </small>
              <small
                v-if="currentRider.ownerName && currentRider.ownerContact"
                class="category"
              >
                <br />
                Owner Details:
                <b title="Owner Name">{{ currentRider.ownerName }}</b> ||
                <b title="Owner Contact">{{ currentRider.ownerContact }}</b>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changeSubscription" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Change Subscription Type</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="changeSubscription">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group asdh-select" name="role">
                    <label>Subscription Type</label>
                    <select class="form-control" v-model="myPackage.package">
                      <option value disabled>Select Subscription Type</option>
                      <option
                        data-tokens=""
                        :value="option.id"
                        v-for="(option, index) in packageList"
                        :key="index"
                      >
                        {{ option.name.toUpperCase() }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-offset-6 col-md-3">
                  <button
                    v-if="myPackage.package !== 0"
                    type="submit"
                    class="btn btn-success"
                    style="margin-top: 20%"
                  >
                    Change Subscription Type
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="info" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Update License Expiry Date</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateLicense()">
              <div class="row">
                <div class="col-md-12">
                  <input-text
                    v-model="currentRow.licenseExpiry"
                    type="date"
                    name="license_expiry"
                    label="License Expiry"
                  ></input-text>
                  <small
                    class="text-center text-danger"
                    v-if="errors.any('license_expiry')"
                    >* {{ errors.first("license_expiry") }}</small
                  >

                  <button
                    type="submit"
                    :disabled="errors.any()"
                    class="pull-right btn btn-success"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bluebook" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Update Bluebook Renew Date</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateBluebook()">
              <div class="row">
                <div class="col-md-12">
                  <input-text
                    v-model="currentRow.bluebookExpiry"
                    type="date"
                    name="bluebook_expiry"
                    label="Bluebook Renew Date"
                  ></input-text>
                  <small
                    class="text-center text-danger"
                    v-if="errors.any('bluebook_expiry')"
                    >* {{ errors.first("bluebook_expiry") }}</small
                  >

                  <button
                    type="submit"
                    :disabled="errors.any()"
                    class="pull-right btn btn-success"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="junction" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Set Delivery Junction</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateAssociation(association.rider)">
              <div class="row">
                <div class="col-md-12">
                  <input-select
                    v-model="association.junction"
                    name="junction"
                    label=" Delivery Junction"
                    :options="junctions"
                  ></input-select>
                  <button
                    type="submit"
                    :disabled="errors.any()"
                    class="pull-right btn btn-success"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-card>
</template>

<script>
import DeliveryDriver from "@utils/models/DeliveryDriver";
import Junction from "@utils/models/DeliveryJunction";
import { index, destroy } from "@utils/mixins/Crud";
// import { mapMutations } from "vuex";
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "DeliveryDriverIndex",

  mixins: [index, destroy],

  data() {
    return {
      imageUrl: Helpers.cameraImage(),
      columns: [
        "Image",
        "Name",
        "Phone",
        "Email",
        "C.Address",
        "Interested",
        "License",
        "Blue Book",
        "Vehicle",
        "Used_Refer_Code",
        "Refer_Count",
        "Joined On",
      ],
      title: "All <b>Delivery Riders</b> need to Verify",
      rows: { data: [], links: {}, meta: {} },
      model: new DeliveryDriver(),
      file: "",
      type: "",
      currentRow: "",
      blockedOnly: false,
      blacklistedOnly: false,
      counts: {
        unverified: 0,
        verified: 0,
        blocked: 0,
        active: 0,
        associated: 0,
        blacklisted: 0,
        incomplete: 0,
      },
      active: "reset",
      selectType: "",
      myPackage: { rider: 0, package: 0 },
      selectedDriverId: 0,
      currentRider: "",
      license: {
        expiry: "",
        image: "",
      },
      bluebook: {
        expiry: "",
        first: "",
        sec: "",
        trd: "",
      },
      packageList: [],
      association: {
        rider: 0,
        junction: 0,
        update: 0,
      },
      junctions: [],
      junction: new Junction(),
    };
  },
  components: {
    Loading,
  },

  methods: {
    setSubscriptionData(riderId, currentSelected) {
      this.myPackage.rider = riderId;
    },
    exportSheet() {
      if (confirm("Are you sure?"))
        window.location = this.model.indexUrl + "/excel-export";
    },
    getSubscriptionList() {
      axios.get(this.model.indexUrl + "/subscription-list").then((response) => {
        this.packageList = response.data.data;
      });
    },
    changeSubscription() {
      axios
        .post("/admin/delivery-driver/change-subscription-type", {
          riderId: this.myPackage.rider,
          packageId: this.myPackage.package,
        })
        .then((response) => {
          if (response.data == "success") {
            alertMessage("Subscription type successfully changed.");
            $("#changeSubscription").modal("hide");
            this.reset();
          } else {
            alertMessage("Something went wrong while processing", "danger");
          }
        })
        .catch(function (error) {
          alertMessage("Something went wrong while processing", "danger");
        });
      this.reset();
    },
    async verifyDriver(row) {
      if (confirm("Are you sure? You cant undo this action.")) {
        let response = await this.model.verify(row.id);
        if (response === "success") {
          // this.reset();
          row.verified = true;
          alertMessage("Rider Verified.");
        }
      }
    },
    changeAssociateStatus(row) {
      this.association.rider = row.id;
      this.association.junction = 0;
      this.association.update = 1;
    },
    updateAssociation(riderId) {
      this.association.rider = riderId;
      if (this.association.update == 0) {
        this.association.junction = 0;
      } else {
        $("#junction").modal("hide");
      }

      if (this.association.update == 1 && this.association.junction == 0) {
        alertMessage(
          "Please select Delivery Junction for selected rider.",
          "danger"
        );
        return;
      }

      axios
        .post("/admin/delivery-driver/change-associated-status", {
          id: riderId,
          junction: this.association.junction,
          update: this.association.update == 1,
        })
        .then((response) => {
          this.reset();
          alertMessage("Action performed successfully");
        });
    },
    async getJunctions() {
      let junctions = await this.junction.getAll();
      this.junctions = junctions.data.map((item) => {
        return {
          id: item.id,
          name: item.location,
        };
      });
    },
    async getVerifiedRider() {
      let riders = await this.model.getVerifiedRider();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.rows.links = riders.links;
      this.rows.meta = riders.meta;
      this.blockedOnly = false;
      this.blacklistedOnly = false;
      this.active = "verified";
    },
    async getBlockedRider() {
      let riders = await this.model.getBlockedRider();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.rows.links = riders.links;
      this.rows.meta = riders.meta;
      this.blockedOnly = true;
      this.blacklistedOnly = false;
      this.active = "blocked";
    },
    async getBlacklistedRider() {
      let riders = await this.model.getBlacklistedRider();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.rows.links = riders.links;
      this.rows.meta = riders.meta;
      this.blockedOnly = false;
      this.blacklistedOnly = true;
      this.active = "blacklisted";
    },
    async getActiveRider() {
      let riders = await this.model.getActiveRiders();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.blockedOnly = false;
      this.blacklistedOnly = false;
      this.active = "active";
    },
    async getAssociatedRider() {
      let riders = await this.model.getAssociatedRiders();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.blockedOnly = false;
      this.blacklistedOnly = false;
      this.active = "associated";
    },
    getIncompleteRider() {
      axios.get("/admin/delivery-driver/incomplete").then((response) => {
        this.rows = { data: [], links: {}, meta: {} };
        this.rows.data = response.data.data;
        this.rows.meta = response.data.meta;
        this.rows.links = response.data.links;
        this.blockedOnly = false;
        this.blacklistedOnly = false;
        this.active = "incomplete";
      });
    },
    async reset() {
      this.currentRider = "";
      this.title = "All <b>Delivery Riders</b> need to Verify";
      let riders = await this.model.getAll();
      this.rows = { data: [], links: {}, meta: {} };
      this.rows.data = riders.data;
      this.rows.links = riders.links;
      this.rows.meta = riders.meta;
      this.blockedOnly = false;
      this.blacklistedOnly = false;
      this.counter();
      this.active = "reset";
    },
    updateLicense(row = null) {
      if (row) {
        this.currentRow = row;
      } else {
        axios
          .post("/admin/delivery-driver/update-expiry-date", {
            id: this.currentRow.id,
            licenseExpiryDate: this.currentRow.licenseExpiry,
          })
          .then((response) => {
            this.currentRow = "";
            this.reset();
            alertMessage("Expiry Date Updated.");
            $("#info").modal().click();
          });
      }
    },
    updateBluebook(row = null) {
      if (row) {
        this.currentRow = row;
      } else {
        axios
          .post("/admin/delivery-driver/update-expiry-date", {
            id: this.currentRow.id,
            bluebookExpireDate: this.currentRow.bluebookExpiry,
          })
          .then((response) => {
            this.currentRow = "";
            this.reset();
            alertMessage("Expiry Date Updated.");
            $("#bluebook").modal().click();
          });
      }
    },
    clearBlock(row) {
      if (confirm("Are you sure? You want to proceed.")) {
        axios
          .post("/admin/delivery-driver/clear-block-blacklist", {
            id: row.id,
            type: "block",
          })
          .then((response) => {
            this.reset();
            alertMessage("Block Cleared.");
          });
      }
    },

    clearBlacklist(row) {
      if (confirm("Are you sure? You want to proceed.")) {
        axios
          .post("/admin/delivery-driver/clear-block-blacklist", {
            id: row.id,
            type: "blacklist",
          })
          .then((response) => {
            this.reset();
            alertMessage("Blacklist Count Cleared.");
          });
      }
    },

    blockRider(riderId) {
      swal({
        text: "Blocking Reason",
        content: "input",
        button: {
          text: "Submit!",
          closeModal: false,
        },
      }).then((reason) => {
        if (!reason || reason.length < 5) {
          return swal("Oh noes!", "Write complete reason!", "error");
        } else {
          axios
            .get(
              this.model.indexUrl +
                "/block?rider=" +
                riderId +
                "&reason=" +
                reason
            )
            .then((response) => {
              if (response.data == "success") {
                this.reset();
                alertMessage("Operation Success.");
              } else {
                alertMessage("Operation failure.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },

    async counter() {
      let riders = await this.model.getAll();
      let verified = await this.model.getVerifiedRider();
      let blocked = await this.model.getBlockedRider();
      let blacklisted = await this.model.getBlacklistedRider();
      let active = await this.model.getActiveRiders();
      let associated = await this.model.getAssociatedRiders();
      this.counts.verified = verified.meta.total;
      this.counts.blocked = blocked.meta.total;
      this.counts.blacklisted = blacklisted.meta.total;
      this.counts.active = active.data.length;
      this.counts.associated = associated.data.length;
      this.counts.unverified = riders.meta.total;

      axios.get("/admin/delivery-driver/incomplete").then((response) => {
        this.counts.incomplete = response.data.meta.total;
      });
    },

    async locationRider(riderId) {
      if (this.authUser.email === "sunbi.mac@gmail.com") {
        let loc = await this.model.getLocationData(riderId);
        this.getLocation(loc.lat, loc.long);
      }
    },

    getLocation(lat, long) {
      var apikey = "27ddafe76ecf4e9994bb61acf05e0243";
      var latitude = lat;
      var longitude = long;

      var api_url = "https://api.opencagedata.com/geocode/v1/json";

      var request_url =
        api_url +
        "?" +
        "key=" +
        apikey +
        "&q=" +
        encodeURIComponent(latitude + "," + longitude) +
        "&pretty=1" +
        "&no_annotations=1";

      return fetch(request_url)
        .then((resp) => resp.json())
        .then(function (data) {
          var suburb = data.results[0].components.suburb;
          var near = data.results[0].components.neighbourhood;
          var road = data.results[0].components.road;
          var city = data.results[0].components.city;
          var locationIs = "";

          if (road) {
            locationIs += road + ", ";
          }

          if (suburb) {
            locationIs += suburb + ", ";
          }

          if (near) {
            locationIs += "Near to " + near + ", ";
          }

          if (city) {
            locationIs += city;
          }
          swal("Rider is in", locationIs);
        })
        .catch(function () {
          console.log("error");
        });
    },

    expandRow(row) {
      this.currentRider = row;
      this.title = row.name;
      this.license.expiry = row.licenseExpiry;
      this.bluebook.expiry = row.bluebookExpiry;
    },
    async licenseData() {
      let res = null;
      var bodyFormData = new FormData();
      bodyFormData.append("expiry", this.license.expiry);
      bodyFormData.append("image", this.license.image);
      bodyFormData.append("rider", this.currentRider.id);

      await axios({
        method: "post",
        url: this.model.indexUrl + "/update-license",
        data: bodyFormData,
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then(function (response) {
          //handle success
          if (response.data.status) {
            res = response.data.data;
            alertMessage("License Detail updated.");
          } else {
            alertMessage(
              "Error occured while updating license detail.",
              "danger"
            );
          }
        })
        .catch(function (response) {
          //handle error
          alertMessage(
            "Error occured while updating license detail.",
            "danger"
          );
        });

      this.currentRider = res;
    },
    async bluebookData() {
      let res = null;
      var bodyFormData = new FormData();
      bodyFormData.append("expiry", this.bluebook.expiry);
      bodyFormData.append("first", this.bluebook.first);
      bodyFormData.append("sec", this.bluebook.sec);
      bodyFormData.append("trd", this.bluebook.trd);
      bodyFormData.append("rider", this.currentRider.id);

      await axios({
        method: "post",
        url: this.model.indexUrl + "/update-bluebook",
        data: bodyFormData,
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then(function (response) {
          //handle success
          if (response.data.status) {
            res = response.data.data;

            alertMessage("Bluebook Detail updated.");
          } else {
            alertMessage(
              "Error occured while updating bluebook details.",
              "danger"
            );
          }
        })
        .catch(function (response) {
          //handle error
          alertMessage(
            "Error occured while updating bluebook details.",
            "danger"
          );
        });
      this.currentRider = res;
    },

    formatDate(date) {
      return moment(date).format("LL");
    },
    formatDateTime(date) {
      return moment(date).format("LLLL");
    },
    copy(no) {
      copyContent(no, "Refer Code Copied to clipboard.");
    },
    showAddress(address) {
      if (address) {
        swal("Full Address", address);
      }
    },
  },

  mounted() {
    this.getModels();
    this.counter();

    this.getSubscriptionList();

    this.getJunctions();
  },
  created() {},
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
.list-group-item {
  user-select: none;
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

.title-right-action1 {
  right: 15px;
  top: 6px;
  float: right;
  margin-left: 10px;
}
</style>
