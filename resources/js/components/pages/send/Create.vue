<template>
  <app-card :title="edit ? 'Modify <b>Discount</b>' : 'Edit <b>Discount</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-6">
          <label>Discount Type</label>
          <select class="form-control" v-model="form.discountType">
            <option value disabled>Select Discount Type</option>
            <option
              data-tokens=""
              :value="option.value"
              v-for="(option, index) in discountType"
              :key="index"
            >
              {{ option.text }}
            </option>
          </select>
          <small class="text-danger" v-if="form.errors.has('discount_type')"
            >{{ form.errors.get("discount_type") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="Discount value"
            type="text"
            name="discountValue"
            v-model="form.discountValue"
            v-validate="'required'"
            :error-text="errors.first('discountValue')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('discount_value')"
            >{{ form.errors.get("discount_value") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="Start Date"
            name="appliedFrom"
            :error-text="errors.first('appliedFrom')"
            datepicker
            v-model="form.appliedFrom"
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('applied_from')"
            >{{ form.errors.get("applied_from") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="End Date"
            name="appliedTill"
            :error-text="errors.first('appliedTill')"
            datepicker
            v-model="form.appliedTill"
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('applied_till')"
            >{{ form.errors.get("applied_till") }}
          </small>
        </div>
        <div class="col-md-12">
          <input-checkbox label="Status" v-model="form.status"></input-checkbox>
          <small class="text-danger" v-if="form.errors.has('status')"
            >{{ form.errors.get("status") }}
          </small>
        </div>
        <div class="col-md-12">
          <button type="submit" class="btn btn-success pull-right">
            {{ edit ? "Update" : "Save" }}
          </button>
          <br />
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Discount from "@utils/models/Discount";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "SendCreate",
  mixins: [store, save],
  data() {
    return {
      edit: false,
      discountType: [
        { text: "Flat Discount", value: "flat" },
        { text: "Percentage Discount", value: "percentage" },
      ],
      selectedDiscountType: "",
      form: new Form({
        discountType: "",
        discountValue: "",
        appliedFrom: "",
        appliedTill: "",
        status: false,
      }),
      model: new Discount(),
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();
      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `faq.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `discount.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
        alertMessage("The given data was invalid.", "danger");
      }
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        appliedFrom,
        appliedTill,
        discountType,
        discountValue,
        status,
      } = data.data;
      this.form = new Form({
        appliedFrom,
        appliedTill,
        discountType,
        discountValue,
        status,
      });
    },
  },
  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    console.log(this.edit + " value");
    if (this.edit) {
      this.getData();
    }
  },
  watch: {},
};
</script>
