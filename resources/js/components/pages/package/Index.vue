<template>
<div>
  <app-card title="All <b>Subscription Package</b>" body-padding="0">
    <template slot="actions">
      <app-btn-link route-name="package.create">Add New</app-btn-link>
    </template>

    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :searchUrl="'/admin/package/get-data?name='"
      :paginate="true"
      :searchHolder="'Search (By Name) ......'"
    >
      <template slot-scope="{ row }">
        <td>{{ row.name }}</td>
        <td>
          {{ row.type === "amount" ? "Rs. " : "" }}{{ row.twoWheelValue }}
          {{ row.type === "percent" ? "%" : "" }}
        </td>
        <td>
          {{ row.type === "amount" ? "Rs. " : "" }}{{ row.fourWheelValue }}
          {{ row.type === "percent" ? "%" : "" }}
        </td>
        <td>{{ row.riderCount }}
          <button type="button"
            @click="setSubscriptionData(row.id, row.name)" class="btn btn-primary action-add btn-ajax btn-link" title="Switch Subscription">Switch Rider</button>
        </td>
        <td>
          <span class="badge">{{ row.hide }}</span>
        </td>
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'package.edit', params: { id: row.id } },
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
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
                    <select class="form-control" v-model="toSubscription">
                      <option value disabled>Select Subscription Type</option>
                      <option
                        data-tokens=""
                        :value="option.id"
                        v-for="(option, index) in packageList"
                        :key="index"
                        :selected ="option.id == currentSubscription"
                      >
                        {{ option.name.toUpperCase() }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-offset-6 col-md-3">
                  <button
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
</div>
</template>

<script>
import Package from "@utils/models/Package";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "PackageIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "Name",
        "2-Wheeler",
        "4-Wheeler",
        "Subscribed Riders",
        "Hidden",
        "Added On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Package(),
      packageList: [],
      currentSubscription : '',
      toSubscription: ''
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    setSubscriptionData(id, name){
      this.currentSubscription = id;
      var modal = $('#changeSubscription').modal()
      var text = "Swithc Riders: From " + name.toUpperCase()
      modal.find('.modal-title').text(text)
      modal.show()
    },
    getSubscriptionList() {
      axios.get("/admin/driver/subscription-list").then((response) => {
        this.packageList = response.data.data;
      });
    },
    changeSubscription() {
      if(this.currentSubscription == this.toSubscription ){
        swal("No Changes to proceed.")
        return;
      }
      swal({
        title: "Are you sure?",
        text: "Once Changed, you will not be able to revert the process!",
        icon: "warning",
        buttons: ["Cancle!", "Proceed!"],
        dangerMode: true,
      }).then((result) => {
        if(result){
          axios
            .post("/admin/driver/switch-subscription-type", {
              packageFrom: this.currentSubscription,
              packageTo: this.toSubscription,
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
        }
      });
      
    },
    reset(){
      axios.get(this.model.indexUrl).then((response) => {
        this.rows = response.data
        this.currentSubscription = ''
        this.toSubscription = ''
      });
    }
  },

  mounted() {
    this.getModels();
    this.getSubscriptionList();
  },
  computed: {
    ...mapGetters(["authUser"]),
  },
};
</script>

<style scoped>
</style>