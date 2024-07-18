<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Rental Package</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-4">
          <input-text
            label="Package Name"
            type="text"
            name="name"
            v-model="form.name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('name')"
            >{{ form.errors.get("name") }}
          </small>
        </div>
        <div class="col-md-4">
          <input-text
            label="Duration"
            type="text"
            name="duration"
            v-model="form.duration"
            v-validate="'required'"
            :error-text="errors.first('duration')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('duration')"
            >{{ form.errors.get("duration") }}
          </small>
        </div>
        <div class="col-md-4">
          <input-text
            label="Distance"
            type="text"
            name="distance"
            v-model="form.distance"
            v-validate="'required'"
            :error-text="errors.first('distance')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('distance')"
            >{{ form.errors.get("distance") }}
          </small>
        </div>

        <div class="col-md-12">
          <h3 class="my-sub-heading">Vehicles in This Package</h3>
        </div>

        <div
          v-for="(vehicle, index) in form.vehicles"
          :key="index"
          class="col-md-12"
        >
          <div class="col-md-4">
            <input-text
              label="Name"
              type="text"
              :name="'name' + index"
              v-validate="'required'"
              v-model="vehicle.name"
              required
              :error-text="errors.first('name' + index)"
            ></input-text>
          </div>
          <div class="col-md-4">
            <input-text
              label="Price"
              type="number"
              :name="'price' + index"
              v-validate="'required'"
              v-model="vehicle.price"
              required
              :error-text="errors.first('price' + index)"
            ></input-text>
          </div>
          <div :class="form.vehicles.length > 1 ? 'col-md-3' : 'col-md-4'">
            <input-text
              label="Description"
              type="text"
              :name="'description' + index"
              v-model="vehicle.description"
              :error-text="errors.first('description' + index)"
            ></input-text>
          </div>
          <div class="col-md-1 text-center" v-if="form.vehicles.length > 1">
            <a
              href="#"
              title="delete"
              v-on:click="removeVehicle()"
              class="btn btn-danger btn-sm"
              style="margin-top: 40px"
            >
              <span class="material-icons">delete</span>
            </a>
          </div>
        </div>
        <br />

        <div class="col-md-12">
          <button
            class="btn btn-warning btn-sm"
            title="add"
            type="button"
            v-on:click="addVehicle()"
          >
            <i class="material-icons">add</i>
            <div class="ripple-container"></div>
          </button>
        </div>

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
import RentalPackage from "@utils/models/RentalPackage";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "RentalPackageCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        name: "",
        duration: "",
        distance: "",
        vehicles: [
          {
            id: 0,
            name: "",
            price: 0,
            description: "",
          },
        ],
        description: "",
      }),
      model: new RentalPackage(),
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
          name: `rental-package.index`,
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
          name: `rental-package.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, duration, distance, vehicles, description } = data.data;

      this.form = new Form({
        name,
        duration,
        distance,
        vehicles,
        description,
      });
    },

    addVehicle() {
      this.form.vehicles.push({
        id: 0,
        name: "",
        price: 0,
        description: "",
      });
    },
    removeVehicle() {
      if (this.form.vehicles.length > 0) {
        this.form.vehicles.pop();
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
