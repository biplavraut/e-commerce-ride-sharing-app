
<template>
  <app-card title="All <b>Q/As</b>" body-padding="0">
    <app-table-sortable
      :columns="columns"
      :rows="rows"
      :paginate="true"
      :searchUrl="'/vendor/product/get-qas/get-data?name='"
    >
      <template slot-scope="{ row }">
        <td>
          {{ row.user.first_name }} {{ row.user.last_name }}
          <small>({{ row.user.phone }})</small>
        </td>
        <td>
          {{ row.product.title }}
          <small>( code: {{ row.product.code }})</small>
        </td>
        <td>{{ row.question }}</td>
        <!-- <td>{{ row.answer }}</td> -->
        <td>{{ formatDate(row.createdAt.date) }}</td>
        <td width="100">
          <button
            type="button"
            title="Answer Now"
            class="btn btn-success btn-ajax"
            @click="answerQA(row)"
          >
            <i class="material-icons">question_answer</i>
          </button>
        </td>
      </template>
    </app-table-sortable>
  </app-card>
</template>

<script>
import ProductQA from "@utils/models/ProductQA";
import { index, destroy } from "@utils/mixins/Crud";
import { mapMutations } from "vuex";

export default {
  name: "VendorProductQAIndex",

  mixins: [index, destroy],

  data() {
    return {
      columns: ["User", "Product", "Question", "Asked On"],
      rows: { data: [], links: {}, meta: {} },
      model: new ProductQA(),
    };
  },

  methods: {
    formatDate(date) {
      return moment(date).format("LLLL");
    },
    answerQA(row) {
      swal({
        text: "Please give the sweet answer",
        content: "input",
        button: {
          text: "Go Ahead",
          closeModal: false,
        },
      }).then((name) => {
        if (name) {
          this.submitAnswer(row, name);
        } else {
          swal("You need to give answer.");
        }
      });
    },
    submitAnswer(row, answer) {
      axios
        .post("/vendor/product/answer-qa", {
          id: row.id,
          answer: answer,
        })
        .then((response) => {
          if (response.data === "success") {
            swal({
              title: "Answered!",
              text: "Thanks",
              icon: "success",
            });
            this.reset();
          } else {
            alertMessage("Something went wrong.", "danger");
          }
        });
    },
    reset() {
      axios.get("/vendor/product/get-qas").then((response) => {
        this.rows.data = response.data.data;
      });
    },
  },

  mounted() {
    this.getModels();
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