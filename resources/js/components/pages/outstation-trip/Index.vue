<template>
  <app-card title="All <b>Outstation Trips</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :paginate="true"
      :rows="rows"
      :searchUrl="'/admin/outstation-trip/get-trip?name='"
    >
      <template slot-scope="{ row }">
        <td :title="row.user.country_code + ' ' + row.user.phone">
          <small
            >{{ row.user.first_name }} {{ row.user.last_name }}
            <small>({{ row.user.phone }})</small></small
          >
        </td>

        <td>
          <small
            v-if="row.driver"
            :title="row.driver.country_code + ' ' + row.driver.phone"
          >
            {{ row.driver.name }}
            <small>({{ row.driver.phone }})</small>
          </small>
          <small v-else>
            <a
              @click.prevent="riderSelection(row)"
              data-toggle="modal"
              data-target="#info"
              title="Set the rider"
            >
              <i class="material-icons">local_taxi</i>
            </a>
          </small>
        </td>

        <td>{{ row.from }}</td>
        <td>{{ row.to }}</td>
        <td>
          {{ row.vehicle }}
          <small title="Total Cost">(Rs.{{ row.price }})</small>
        </td>
        <td>
          <small>{{ formatDate(row.startsAt) }}</small>
        </td>
        <td>
          <small v-if="row.status === 'completed'">
            {{ formatDate(row.completedAt) }}
          </small>
          <small v-else>-</small>
        </td>
        <td>
          <small :title="formatDate(row.createdAt)">{{
            formatBookedDate(row.createdAt)
          }}</small>
        </td>

        <td width="50">
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              delete: row.id,
            }"
          ></app-actions>
        </td>
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
            <h4 class="modal-title">Choose Rider/Captain</h4>
          </div>
          <div class="modal-body">
            <form @submit.prevent="riderSelection()">
              <div class="row">
                <div class="col-md-12">
                  <input-select
                    v-model="riderId"
                    name="rider"
                    label="Rider"
                    :options="riders"
                  ></input-select>
                  <small
                    class="text-center text-danger"
                    v-if="errors.any('rider')"
                    >* {{ errors.first("rider") }}</small
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
  </app-card>
</template>

<script>
import OutstationTrip from "@utils/models/OutstationTrip";
import { index, destroy } from "@utils/mixins/Crud";

export default {
  name: "OutstationTripIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: [
        "User",
        "Driver",
        "From",
        "To",
        "Vehicle",
        "Start Time",
        "Completed At",
        "Booked On",
      ],
      rows: { data: [], links: {}, meta: {} },
      model: new OutstationTrip(),
      riders: [],
      currentRow: "",
      riderId: "",
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    formatBookedDate(date) {
      return moment(date).format("LL");
    },
    reset() {
      axios.get("/admin/outstation-trip").then((response) => {
        this.rows.data = response.data.data;
      });
    },
    async getRiders() {
      let riders = await this.model.getRiders();
      this.riders = riders.data.map((rider) => {
        return {
          id: rider.id,
          name: rider.name + " (" + rider.address + ")",
          phone: rider.phone,
          email: rider.email,
        };
      });
    },

    riderSelection(row = null) {
      if (row) {
        this.currentRow = row;
      } else {
        axios
          .post("/admin/outstation-trip/update-rider", {
            tripId: this.currentRow.id,
            rider: this.riderId,
          })
          .then((response) => {
            this.currentRow = "";
            this.riderId = "";
            this.reset();
            alertMessage("Rider has been set.");
            $("#info").modal().click();
          });
      }
    },
  },

  mounted() {
    this.getModels();

    this.getRiders();
  },
  created() {},
  watch: {},
};
</script>

<style scoped>
.card-title {
  padding: 10px 15px;
  margin: 0;
  font-weight: 400;
  /* background-color : #337AB7; */
  color: #666666;
}
</style>