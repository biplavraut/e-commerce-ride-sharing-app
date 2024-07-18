<template>
  <app-card :title="edit ? 'Edit ' + form.code : 'Add New <b>Coupon Code</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-4">
          <input-text
            label="Code"
            name="code"
            v-validate="'required'"
            v-model="form.code"
            required
            :error-text="errors.first('code')"
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Amount"
            name="amount"
            type="number"
            v-validate="'required'"
            v-model="form.amount"
            required
            :error-text="errors.first('amount')"
          ></input-text>
        </div>

        <div class="col-md-4">
          <input-text
            label="Valid Till"
            name="valid_till"
            type="date"
            v-validate="'required'"
            v-model="form.validTill"
            required
            :error-text="errors.first('valid_till')"
          ></input-text>
        </div>

        <div class="col-md-12">
          <button
            type="submit"
            :disabled="form.errors.any(errors.any())"
            class="btn btn-success pull-right"
          >
            {{ edit ? "Update" : "Save" }}
          </button>
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Coupon from "@utils/models/Coupon";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "CouponCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        code: "",
        amount: "",
        validTill: "",
      }),
      model: new Coupon(),
    };
  },

  methods: {
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { code, amount, validTill } = data.data;

      this.form = new Form({
        code,
        amount,
        validTill,
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },

  watch: {},
};
</script>
