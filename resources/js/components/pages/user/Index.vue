<template>
  <div>
    <app-card title="App <b>Users</b>" body-padding="0">
      <ul class="nav nav-pills nav-pills-warning" style="padding: 5px" v-if="this.authUser.type ==='admin' || this.authUser.type ==='superadmin'">
        <li :class="state === 'all' ? 'active' : ''" @click.prevent="reset">
          <a href="#all" data-toggle="tab" aria-expanded="true"
            >All <span class="badge">{{ counter.all }}</span></a
          >
        </li>
        <li
          :class="state === 'block' ? 'active' : ''"
          @click.prevent="blockedList"
        >
          <a href="#blocked" data-toggle="tab" aria-expanded="true"
            >Blocked <span class="badge">{{ counter.blocked }}</span></a
          >
        </li>

        <li
          :class="state === 'active' ? 'active' : ''"
          @click.prevent="activeList"
        >
          <a href="#active" title="Logged-In Within 30 Days" data-toggle="tab" aria-expanded="true"
            >Active<span class="badge">{{ counter.active }}</span></a
          >
        </li>
        <li
          :class="state === 'passive' ? 'active' : ''"
          @click.prevent="passiveList"
        >
          <a href="#passive" title="Not Logged-In Within 30 Days" data-toggle="tab" aria-expanded="true"
            >Passive<span class="badge">{{ counter.passive }}</span></a
          >
        </li>
        <li :class="state === 'top' ? 'active' : ''" @click.prevent="topList">
          <a href="#top" title="Users with Reward Points" data-toggle="tab" aria-expanded="true"
            >Top 50 Performer<span class="badge">{{ counter.top }}</span></a
          >
        </li>
        <li
          :class="state === 'elite' ? 'active' : ''"
          @click.prevent="eliteList"
        >
          <a href="#elite" data-toggle="tab" aria-expanded="true"
            >gogoElite<span class="badge">{{ counter.elite }}</span></a
          >
        </li>
        <li>
          <span>*Ref:Implement datewise sorting</span>
        </li>
      </ul>
      <app-table-sortable
        :columns="columns"
        :rows="rows"
        :paginate="true"
        :searchUrl="'/admin/user/get-data?name='"
        :searchHolder="'Search (By GUID, Name, Mobile, Refer Code)'"
        reverse
      >
        <template slot-scope="{ row }">
          <td
            @click="copy(row.userId)"
            style="cursor: pointer"
            title="click to copy"
          >
            <span
              class="badge"
              title="Registered From"
              :style="
                row.registeredFrom === 'app'
                  ? 'background-color: blue'
                  : 'background-color: green'
              "
              >{{ row.registeredFrom }}</span
            ><br />
            {{ row.userId }}
            <span class="badge">{{ row.elite ? "gogoElite" : "Normal" }}</span>
          </td>
          <td width="100">
            <a :href="row.image" target="_blank">
              <img :src="row.image" style="width: 50px; height: 50px; border-radius: 50%" />
            </a>
          </td>
          <td>
            {{ row.firstName }} {{ row.lastName }}</td>
          <td>
            <a @click.prevent="row.phone ? copy(row.phone) : ''"
            title="Click to copy" href="#"> {{ row.phone }} </a><br>
            <a v-bind:href=" `mailto:${row.email ? row.email : '-'}` " >{{ row.email ? row.email : "-" }}</a>
          </td>
          <td @click="showAddress(row.address)" class="cursor">
            {{ row.address ? row.address.substring(0, 10) + ".." : "-" }}
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
            title="Click to copy">{{ row.myReferCode }} - ({{ row.referCount }})</td>
          <td> <small>Reward: </small> {{ row.rewardPoint | commaNumberFormat }}<br>
            <small>Wallet: </small>{{ row.gogoWallet | commaNumberFormat }}</td>
          <td><small>{{ formatDate(row.createdAt) }}</small></td>
          <td><small>{{ row.recentLogin }}</small></td>
          <td width="100">{{ row.blocked ? "Blocked" : "-" }}<br /></td>
          <td width="100">
            <!-- <button
              class="btn btn-primary btn-xs"
              @click="changeUserPassword(row.id)"
            >
              Change Password
              <div class="ripple-container"></div>
            </button> -->
            <router-link :to="{name:'user.transaction', params:{userId:row.id}}" class="btn btn-primary action-add btn-ajax btn-link" type="button" title="View Transactions"><i class="material-icons">visibility</i></router-link>
            <button
              type="button"
              :title="row.blocked ? 'Unblock Now' : 'Block Now'"
              :class="
                row.blocked
                  ? 'btn btn-success btn-ajax'
                  : 'btn btn-danger btn-ajax'
              "
              @click.prevent="blockUser(row)"
            >
              <i class="material-icons">{{
                row.blocked ? "vpn_key" : "block"
              }}</i>
              <div class="ripple-container"></div>
            </button>
            <button
              v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
              class="btn btn-default btn-ajax"
              title="Send Notification"
              @click="customNotification(row.id, row.firstName, row.lastName)"
            >
            <i class="material-icons">notifications</i>
              
              <div class="ripple-container"></div>
            </button>
            <button
              v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
              class="btn btn-success btn-xs"
              @click="addWalletPoint(row.id, row.firstName, row.lastName)"
            >
              Add Wallet Point
              <div class="ripple-container"></div>
            </button>
          </td>
        </template>
      </app-table-sortable>
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
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="amount" class="col-form-label">Points*</label>
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
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="submitWalletPoint()"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of input Model -->
    <!-- Start of Input Modal  -->
    <div
      class="modal fade"
      id="sendNewNotification"
      tabindex="-1"
      role="dialog"
      aria-labelledby="sendNewNotificationLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              id="modelNotifyClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="sendNewNotificationLabel"></h5>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="title" class="col-form-label">Title*</label>
                    <input
                      type="text"
                      v-model="notifyForm.title"
                      id="title"
                      class="form-control"
                      required
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="type" class="col-form-label">Type*</label>
                    <input
                      type="text"
                      v-model="notifyForm.type"
                      id="type"
                      class="form-control"
                      required
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="message" class="col-form-label">Message*</label>
                    <input
                      type="text"
                      v-model="notifyForm.message"
                      class="form-control"
                      id="message"
                      required
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="sendNotification()"
            >
              Send
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
import User from "@utils/models/User";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "UserIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      columns: [
        "GUID",
        " ",
        "Full_Name",
        "Contact",
        "Address",
        "Used Refer Code",
        "Refer Count",
        "gogoPoint",
        "Joined_On",
        "Last Login",
        "Status",
      ],
      model: new User(),
      counter: {
        all: null,
        blocked: null,
        active: null,
        passive: null,
        top: null,
        elite: null,
      },
      form: new Form({
        user_id: "",
        amount: "",
        remarks: "",
      }),
      notifyForm: new Form({
        user: "",
        title: "",
        message: "",
        type: ""
      }),
      addfund: false,
      notify: false,
      state: "all",
    };
  },

  methods: {
    changeUserPassword(userid) {
      if (
        confirm("Are you sure? You want to reset the password for this user.?")
      ) {
        axios
          .post("/admin/user/changepassword", {
            id: userid,
          })
          .then((response) => {
            if (response.status == 200) {
              alertMessage("New password successfully sent to user though SMS");
            } else {
              alertMessage("Something went wrong while processing", "danger");
            }
          })
          .catch(function (error) {
            alertMessage("Something went wrong while processing", "danger");
          });
      }
    },
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    copy(no) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = no;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);
      alertMessage("Content copied to clipboard.");
    },
    reset() {
      this.state = "all";
      if(this.authUser.type ==='admin' || this.authUser.type ==='superadmin'){
        axios.get(this.model.indexUrl).then((response) => {
          this.rows.data = response.data.data;
          this.rows.links = response.data.links;
          this.rows.meta = response.data.meta;
          this.counter.all = response.data.meta.total;
        });
      }
      
    },
    blockedList() {
      this.state = "block";
      axios.get(this.model.indexUrl + "/state?block=1").then((response) => {
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
        this.counter.blocked = response.data.meta.total;
      });
    },
    activeList() {
      this.state = "active";
      axios.get(this.model.indexUrl + "/state?active=1").then((response) => {
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
        this.counter.active = response.data.meta.total;
      });
    },
    passiveList() {
      this.state = "passive";
      axios.get(this.model.indexUrl + "/state?passive=1").then((response) => {
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
        this.counter.passive = response.data.meta.total;
      });
    },
    topList() {
      this.state = "top";
      axios.get(this.model.indexUrl + "/state?top=1").then((response) => {
        this.rows.data = response.data.data;
        this.rows.links = {};
        this.rows.meta = {};
        this.counter.top = response.data.data.length;
      });
    },
    eliteList() {
      this.state = "elite";
      axios.get(this.model.indexUrl + "/state?elite=1").then((response) => {
        this.rows.data = response.data.data;
        this.rows.links = response.data.links;
        this.rows.meta = response.data.meta;
        this.counter.elite = response.data.data.length;
      });
    },
    counterList() {
      axios.get(this.model.indexUrl + "/state?block=1").then((response) => {
        this.counter.blocked = response.data.meta.total;
      });
      axios.get(this.model.indexUrl + "/state?active=1").then((response) => {
        this.counter.active = response.data.meta.total;
      });
      axios.get(this.model.indexUrl + "/state?passive=1").then((response) => {
        this.counter.passive = response.data.meta.total;
      });
      axios.get(this.model.indexUrl + "/state?top=1").then((response) => {
        this.counter.top = response.data.data.length;
      });
      axios.get(this.model.indexUrl + "/state?elite=1").then((response) => {
        this.counter.elite = response.data.meta.total;
      });
    },
    blockUser(user) {
      if (confirm("Are you sure? You want to execute this.")) {
        axios
          .get(
            this.model.indexUrl +
              "/block?id=" +
              user.id +
              "&state=" +
              user.blocked
          )
          .then((response) => {
            alertMessage(response.data);
            var state = user.blocked;
            user.blocked = state ? false : true;
          });
      }
    },
    addWalletPoint(user, fn, ln) {
      this.addfund = true;
      this.form.reset();
      this.form.user_id = user;
      var modal = $("#addNewModel").modal();
      var text = "Add Advance Wallet Point to " + fn + " " + ln;
      modal.find(".modal-title").text(text);
      modal.show();
    },
    submitWalletPoint() {
      swal({
        title: "Are you sure?",
        text: "Once Added, you will not be able to revert the amount!",
        icon: "warning",
        buttons: ["Cancel!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
        if (result) {
          if (!this.form.amount || !this.form.user_id || !this.form.remarks) {
            swal("Invalid Input");
            throw null;
          } else {
            $("#modelClose").click();
            this.form.post("/admin/elite-user-wallet/add").then((response) => {
              if (response.status) {
                alertMessage(response.message);
                this.form.reset();
                this.eliteList();
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
    customNotification(user, fn, ln) {
      this.notify = true;
      this.notifyForm.reset();
      this.notifyForm.user = user;
      var modal = $("#sendNewNotification").modal();
      var text = "Send notification to " + fn + " " + ln;
      modal.find(".modal-title").text(text);
      modal.show();
    },
    sendNotification() {
      swal({
        title: "Are you sure?",
        text: "The message will reflect in selected user device!",
        icon: "warning",
        buttons: ["Cancel!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
        if (result) {
          if (!this.notifyForm.title || !this.notifyForm.user || !this.notifyForm.message || !this.notifyForm.type) {
            swal("Invalid Input");
            throw null;
          } else {
            $("#modelNotifyClose").click();
            this.notifyForm.post(this.model.indexUrl +"/custom-notification").then((response) => {
              if (response) {
                alertMessage(response.message);
                this.notifyForm.reset();
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
  created() {
    // this.getModels();
    if(this.authUser.type ==='admin' || this.authUser.type ==='superadmin'){
      this.reset();
      this.counterList();
    }
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
</style>
