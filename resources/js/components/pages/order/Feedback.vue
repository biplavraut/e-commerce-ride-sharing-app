<template>
  <app-card title="All <b>Order Feedbacks</b>" body-padding="0">
    <app-table-sortable :columns="columns" :rows="rows" :paginate="true">
      <template slot-scope="{ row }">
        <span> Order No</span><br />
        <router-link
          :to="{
                name: 'order.detail',
                params: { id: row.order.id ,},
              }"
          title="Order Detail"
        >
          <span class="badge cursor" title="OnDemand Service">{{
            row.order.ref_number
          }}</span>
        </router-link>
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <br />
          {{ row.user.phone }}
        </td>
        <td @click="showFeedback(row)" style="cursor: pointer">
          {{ row.feedback.substring(0, 100) }}..
        </td>
        <td @click="updateFeedback(row)" style="cursor: pointer">
          <i class="material-icons">reply</i>
        </td>
        <td>{{ row.createdAt }}</td>
        <td
          width="100"
          v-if="authUser.type === 'admin' || authUser.type === 'superadmin'"
        >
          <app-actions
            @deleteItem="deleteModel"
            :actions="{
              delete: row.id,
            }"
          ></app-actions>
        </td>
        <td v-else>-</td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import OrderFeedback from "@utils/models/OrderFeedback";
import moment from "moment";
import { index, destroy } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  name: "OrderFeedbackIndex",

  mixins: [index, destroy],

  data() {
    return {
      rows: { data: [], links: {}, meta: {} },
      radioGroup: 1,
      columns: ["Order_Ref", "User", "Feedback", "Response", "Posted On"],
      model: new OrderFeedback(),
      response: {
        feedbackId: 0,
      },
    };
  },

  methods: {
    showFeedback(row) {
      swal(
        row.user.first_name + " " + row.user.last_name + "'s Feedback",
        row.feedback
      );
    },
    updateFeedback(row) {
      this.response.feedbackId = row.id;
      swal({
        text: "Explain how did you respond?",
        content: "input",
        button: {
          text: "Submit!",
          closeModal: true,
        },
      }).then((log) => {
        if (!log || log.length < 5) {
          return swal("Oh noes!", "Write something more clearly!", "error");
        } else {
          axios
            .post(this.model.indexUrl + "/respond", {
              feedbackId: row.id,
              respond: log,
            })
            .then((response) => {
              if (response.data === "success") {
                alertMessage("Respond stored.");
                this.rows.data = this.rows.data.filter(
                  (item) => item.id !== row.id
                );
              } else {
                alertMessage("Action cannot be processed.", "danger");
              }
            });
          swal.stopLoading();
          swal.close();
        }
      });
    },
  },
  created() {},
  mounted() {
    this.getModels();
  },
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
