<template>
  <app-card
    :title="edit ? 'Edit ' + form.vehicle : 'Add New <b>Riding Fare</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-3">
          <!-- <input-select
            v-model="form.vehicle"
            name="vehicle"
            label="Vehicle Type *"
            :options="vehicles"
            v-validate="'required'"
            :error-text="errors.first('vehicle')"
            required
          ></input-select> -->
          <div class="form-group asdh-select" name="role">
            <label>Vehicle Type *</label>
            <select class="form-control" v-model="form.vehicle">
              <option value disabled>Select Vehicle Type</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in vehicles"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>

          <small class="text-danger" v-if="form.errors.has('vehicle')"
            >{{ form.errors.get("vehicle") }}
          </small>
        </div>
        <div class="col-md-3">
          <input-text
            label="Price (per km)"
            type="number"
            name="price"
            v-model="form.price"
            v-validate="'required'"
            :error-text="errors.first('price')"
            required
          ></input-text>
        </div>
        <div class="col-md-3">
          <input-text
            label="Flat Price"
            type="number"
            name="flat_price"
            v-model="form.flatPrice"
            :error-text="errors.first('flat_price')"
          ></input-text>
        </div>
        <div class="col-md-3">
          <input-text
            label="Night Surge (Increase in Price by times) (e.g. org price=100, addonSurge=1.5, newPrice=150)"
            type="text"
            name="night_surge"
            v-model="form.nightSurge"
            min="1"
            :error-text="
              form.nightSurge < 1
                ? 'Price must greater or equal to 1'
                : errors.first('night_surge')
            "
          ></input-text>
        </div>
      </div>

      <div class="row">
        <div class="col-md-10">
          <h3 class="my-sub-heading">Surges</h3>
        </div>
      </div>

      <div v-for="(surge, index) in form.surges" :key="index">
        <div class="row">
          <div class="col-md-3">
            <input-text
              label="Title"
              type="text"
              :name="'title' + index"
              v-model="surge.title"
              :error-text="errors.first('title' + index)"
            ></input-text>
          </div>
          <div class="col-md-3">
            <input-text
              label="From"
              type="datetime-local"
              :name="'from' + index"
              v-validate="'required'"
              v-model="surge.from"
              :error-text="errors.first('from' + index)"
              required
            ></input-text>
          </div>
          <div class="col-md-3">
            <input-text
              label="To"
              type="datetime-local"
              :name="'to' + index"
              v-validate="'required'"
              v-model="surge.to"
              :error-text="errors.first('to' + index)"
              required
            ></input-text>
          </div>

          <div class="col-md-2">
            <input-text
              label="Surge (Increase in Price by times) (e.g. org price=100, addonSurge=1.5, newPrice=150)"
              type="text"
              :name="'price' + index"
              v-validate="'required'"
              v-model="surge.price"
              min="1"
              :error-text="
                surge.price < 1
                  ? 'Price must greater or equal to 1'
                  : errors.first('price' + index)
              "
              required
            ></input-text>
          </div>

          <div class="col-md-1 text-center" v-if="form.surges.length > 0">
            <a
              href="#"
              title="delete"
              v-on:click="removeSurge(surge.id)"
              class="btn btn-danger btn-sm"
              style="margin-top: 40px"
            >
              <span class="material-icons">delete</span>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <button
            class="btn btn-warning btn-sm"
            title="add"
            type="button"
            v-on:click="addSurge()"
          >
            <i class="material-icons">add</i>
            <div class="ripple-container"></div>
          </button>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <app-quill-editor
            label="Description"
            name="description"
            v-model="form.description"
            :error-text="errors.first('description')"
          ></app-quill-editor>

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
import RidingFare from "@utils/models/RidingFare";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "RidingFareCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        vehicle: "",
        price: 0,
        flatPrice: 0,
        nightSurge: 1,
        description: "",
        surges: [],
      }),
      vehicles: [
        {
          id: "Bike",
          name: "Bike",
        },
        {
          id: "Taxi",
          name: "Taxi",
        },
        {
          id: "Premium Taxi",
          name: "Premium Taxi",
        },
      ],
      model: new RidingFare(),
    };
  },

  methods: {
    async storeData() {
      if (this.form.nightSurge < 1) {
        alertMessage(
          "Night Addon Surge must be greater or equal to 1.",
          "danger"
        );
        return;
      }
      this.form.errors.clear();
      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `riding-fare.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async updateData() {
      if (this.form.nightSurge < 1) {
        alertMessage(
          "Night Addon Surge must be greater or equal to 1.",
          "danger"
        );
        return;
      }
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `riding-fare.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { vehicle, price, flatPrice, nightSurge, description, surges } =
        data.data;

      this.form = new Form({
        vehicle,
        price,
        description,
        flatPrice,
        nightSurge,
        surges,
      });

      this.vehicles = [
        {
          id: "Bike",
          name: "Bike",
        },
        {
          id: "Taxi",
          name: "Taxi",
        },
        {
          id: "Premium Taxi",
          name: "Premium Taxi",
        },
      ];
    },
    addSurge() {
      this.form.surges.push({
        id: 0,
        title: "",
        from: "",
        to: "",
        price: 1,
      });
    },
    removeSurge(surgeId) {
      if (surgeId && this.edit) {
        this.model.deleteSurge(surgeId);
        this.form.surges = this.form.surges.filter((item) => {
          if (item.id !== surgeId) {
            return item;
          }
        });
      } else {
        if (this.form.surges.length > 0) {
          this.form.surges.pop();
        }
      }
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
