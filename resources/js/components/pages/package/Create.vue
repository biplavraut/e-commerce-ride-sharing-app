<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Subscription Package</b>'"
  >
    <template slot="actions">
      <app-btn-link route-name="package.create">Add New</app-btn-link>
    </template>
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-6">
          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            required
            v-model="form.name"
            :error-text="errors.first('name')"
          ></input-text>
        </div>

        <div class="col-md-6">
          <div class="form-group asdh-select" name="role">
            <label>Subscription Type</label>
            <select class="form-control" v-model="form.type">
              <option value disabled>Select Subscription Type</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in types"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>
          <small class="text-center text-danger" v-if="errors.any('type')"
            >* {{ errors.first("type") }}</small
          >
        </div>

        <div class="col-md-6">
          <input-text
            label="2-Wheeler Price"
            name="two_wheel_value"
            v-model="form.twoWheelValue"
            :error-text="errors.first('two_wheel_value')"
            v-validate="'required'"
            required
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="4-Wheeler Price"
            name="four_wheel_value"
            v-model="form.fourWheelValue"
            :error-text="errors.first('four_wheel_value')"
            v-validate="'required'"
            required
          ></input-text>
        </div>
        <div class="col-md-12">
          <div class="form-group asdh-select" name="role">
            <label>Payment Duration</label>
            <select class="form-control" v-model="form.duration">
              <option value disabled>Select Payment Duration</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in durations"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>
          <small class="text-center text-danger" v-if="errors.any('duration')"
            >* {{ errors.first("duration") }}</small
          >
        </div>

        <div class="col-md-12">
          <input-checkbox label="Hide" v-model="form.hide"></input-checkbox>

          <button
            type="submit"
            :disabled="this.form.errors.any(errors.any())"
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
import Package from "@utils/models/Package";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "PackageCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        name: "",
        type: "",
        duration: "",
        twoWheelValue: "",
        fourWheelValue: "",
        hide: false,
      }),
      model: new Package(),
      types: [
        {
          id: "amount",
          name: "Amount",
        },
        {
          id: "percent",
          name: "Percentage",
        },
      ],
      durations: [
        {
          id: "per-ride",
          name: "Per Ride",
        },
        {
          id: "monthly",
          name: "Monthly",
        },
        {
          id: "yearly",
          name: "Yearly",
        },
      ],
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
      let { name, type, twoWheelValue, fourWheelValue, duration, value, hide } =
        data.data;

      this.form = new Form({
        name,
        type,
        duration,
        twoWheelValue,
        fourWheelValue,
        hide,
      });

      this.types = [
        {
          id: "amount",
          name: "Amount",
        },
        {
          id: "percent",
          name: "Percentage",
        },
      ];

      this.durations = [
        {
          id: "per-ride",
          name: "Per Ride",
        },
        {
          id: "monthly",
          name: "Monthly",
        },
        {
          id: "yearly",
          name: "Yearly",
        },
      ];
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      // this.imageUrl = Helpers.loadingImage();
      this.getData();
    }
  },

  watch: {},
};
</script>
