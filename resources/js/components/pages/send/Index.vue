<template>
  <app-card title="Gogo <b>Send</b>" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows">
      <template slot-scope="{ row }">
        <td>
          {{ row.id }}
          <span class="badge" v-bind:style="{ background: row.dynamicColor }">{{
            row.agoTime
          }}</span>
        </td>
        <td>
          {{ row.user.firstName }} {{ row.user.lastName }}<br />
          <small>{{ row.user.phone }}</small>
        </td>
        <td>
          {{ row.delivery_item_type.name }}<br /><span
            class="badge badge-primary"
            >{{ row.distance_in_km }} km</span
          >
        </td>
        <td>
          {{ row.pickup_location_name }}<br /><span class="badge badge-primary"
            >{{ row.pickup_date }} @ {{ row.pickup_time }}</span
          >
          <small v-if="row.pickup_comment.length > 0"
            ><i class="fa fa-comment"></i> {{ row.pickup_comment }}</small
          >
        </td>
        <td>
          {{ row.delivery_location_name }}<br /><span
            class="badge badge-primary"
            >{{ row.delivery_date }} @ {{ row.delivery_time }}</span
          >
          <small v-if="row.delivery_comment.length > 0"
            ><i class="fa fa-comment"></i> {{ row.delivery_comment }}</small
          >
        </td>
        <td style="min-width:250px;">
          <small>
            Flat Amout: Rs. {{ row.extra_column.flatPrice }}<br />
            Distance Amount: Rs. {{ row.extra_column.distancePrice }}<br />
            Weight Amout: Rs. {{ row.extra_column.weightPrice }}<br />
            Discount Amout: Rs. {{ row.extra_column.discountAmount }}<br />
            Net Amount: Rs.
            {{ row.extra_column.totalPriceAfterDiscount }}</small
          >
        </td>
        <td>COD</td>
        <td>{{ row.status == true ? "active" : "inactive" }}</td>
        <td v-if="!row.delivery">
          <div class="col-md-4">
            <button
              type="button"
              title="Assign Rider"
              class="btn btn-success btn-ajax"
              data-toggle="modal"
              data-target="#assignRider"
              @click="getRiders(row.id)"
            >
              <i class="material-icons">event_seat</i>
              <div class="ripple-container"></div>
            </button>
          </div>
          <!--<div class="col-md-4">
            <button
              type="button"
              title="Dispatch"
              class="btn btn-warning btn-ajax"
              @click.prevent="dispatchOrder(row.id)"
            >
              <i class="material-icons">done</i>
              <div class="ripple-container"></div>
            </button>
          </div> -->
          <div class="col-md-4">
            <button
              type="button"
              title="Delete"
              class="btn btn-danger btn-ajax"
              @click.prevent="deleteOrder(row.id)"
            >
              <i class="material-icons">close</i>
              <div class="ripple-container"></div>
            </button>
          </div>
        </td>
        <!-- <td width="100">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              edit: { name: 'discount.edit', params: { id: row.id } }
            }"
          ></app-actions>
        </td> -->
      </template>
    </app-table-sortable>
    <div class="modal fade" id="assignRider" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Assign rider for this delivery</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="assignRiderForDelivery">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" v-if="!!sendData.riders">
                    <div class="list-group">
                      <div
                        v-for="(eachRider, index) in sendData.riders.data"
                        :key="index"
                        :value="eachRider.id"
                      >
                        <input
                          type="radio"
                          name="selectrider"
                          :value="eachRider.id"
                          :id="eachRider.id"
                          v-model="sendData.assignedRider"
                        />
                        <label class="list-group-item" :for="eachRider.id"
                          >{{ eachRider.name }}:{{ eachRider.phone }}
                          <div class="row">
                            <small class="col-sm-3">Today's Summary:</small>
                            <small class="col-sm-3"
                              >Assigned:{{ eachRider.totalAssigned }}</small
                            >
                            <small class="col-sm-3"
                              >Delivered:{{ eachRider.totalDelivered }}</small
                            >
                            <small class="col-sm-3"
                              >Remaining:{{
                                eachRider.totalAssigned -
                                  eachRider.totalDelivered
                              }}</small
                            >
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-9 col-md-3">
                  <button
                    v-if="sendData.assignedRider"
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
  </app-card>
</template>

<script>
import Send from "@utils/models/Send";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "SendIndex",
  mixins: [index, destroy],
  data() {
    return {
      columns: [
        "#",
        "User",
        "Send Details",
        "Pickup Details",
        "Delivery Details",
        "Price Details",
        "Payment Type",
        "Status",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new Send(),
      sendData: {
        riders: null,
        assignedRider: "",
        sendId: "",
      },
    };
  },

  methods: {
    getRiders(sendId) {
      this.sendData.sendId = sendId;
      axios.get("/admin/get-rider/").then((response) => {
        if (response.data != null) {
          this.sendData.riders = response.data;
        } else {
          alertMessage("Unable to retrive driver", "danger");
        }
      });
    },
    assignRiderForDelivery() {
      axios
        .post("/admin/assign-delivery", {
          sendId: this.sendData.sendId,
          rider_id: this.sendData.assignedRider,
          type: "send",
        })
        .then((response) => {
          if (response.status === true) {
            alertMessage("Delivery successfully assigned to rider.");
            $("#assignRider").modal("hide");
            this.reset();
          } else {
            alertMessage("Something went wrong while processing", "danger");
          }
        })
        .catch(function(error) {
          alertMessage("Something went wrong while processing", "danger");
        });
      this.reset();
    },
  },

  mounted() {
    this.getModels();
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
</style>
