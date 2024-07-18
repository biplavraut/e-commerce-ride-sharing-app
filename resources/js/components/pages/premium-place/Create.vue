<template>
  <app-card :title="edit ? 'Edit ' + form.location : 'Add New <b>Place</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-md-12">
          <input-text
            label="Location Name"
            name="name"
            v-validate="'required'"
            v-model="form.location"
            required
            :error-text="errors.first('name')"
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Latitude"
            name="latitude"
            v-model="form.lat"
            :error-text="errors.first('latitude')"
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Longitude"
            name="longitude"
            v-model="form.long"
            :error-text="errors.first('longitude')"
          ></input-text>
        </div>

        <div class="col-md-6">
          <input-text
            label="Addon Surge (Increase in Price by times) (e.g. org price=100, addonSurge=1.5, newPrice=150)"
            type="text"
            name="price"
            v-model="form.price"
            min="1"
            :error-text="
              form.price < 1
                ? 'Price must greater or equal to 1'
                : errors.first('price')
            "
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Radius (km)"
            type="number"
            name="radius"
            v-model="form.radius"
            :error-text="errors.first('radius')"
          ></input-text>
        </div>

        <div class="col-md-12" v-if="form.popular">
          <input-text
            label="Outstation Extra Charge"
            type="text"
            name="outstation_price"
            v-model="form.outstationPrice"
            :error-text="errors.first('outstation_price')"
          ></input-text>
        </div>

        <div class="col-md-3">
          <input-checkbox
            label="Set as Outstation Destination"
            v-model="form.popular"
          ></input-checkbox>
        </div>

        <div class="col-md-3">
          <input-checkbox label="Hide" v-model="form.hide"></input-checkbox>
        </div>

        <div class="col-md-6">
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
import PremiumPlace from "@utils/models/PremiumPlace";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "PremiumPlaceCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        image: "",
        location: "",
        lat: "",
        long: "",
        outstationPrice: 0,
        price: 1,
        radius: 0,
        popular: false,
        hide: false,
      }),
      model: new PremiumPlace(),
    };
  },

  methods: {
    async storeData() {
      if (this.form.price < 1) {
        alertMessage("Addon Surge must be greater or equal to 1.", "danger");
        return;
      }

      this.form.errors.clear();

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `premium-place.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async updateData() {
      if (this.form.price < 1) {
        alertMessage("Addon Surge must be greater or equal to 1.", "danger");
        return;
      }
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image;
        this.model.cache.invalidate();
        this.$router.push({
          name: `premium-place.index`,
        });
        alertMessage("Data updated successfully.");
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        image150,
        location,
        outstationPrice,
        lat,
        long,
        price,
        radius,
        popular,
        hide,
      } = data.data;

      this.form = new Form({
        image: "",
        location,
        lat,
        long,
        price,
        outstationPrice,
        radius,
        popular,
        hide,
      });
      this.imageUrl = image150;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },

  watch: {
    "form.image": function (val) {
      let type = typeof val;
      if (type === "object") {
        this.form.errors.clear("image");
      }
    },
  },
};
</script>
