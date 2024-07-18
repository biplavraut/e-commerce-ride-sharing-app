<template>
<div class="row">
  <div class="col-md-9">
    <div id="myMap" class="z-depth-1-half map-container" style="height: 500px"></div>
  </div>
  <div class="col-md-3">
    <app-card :title="edit ? 'Edit ' + form.location : 'Add New <b>Junction</b>'">
      <form @submit.prevent="saveData">
        <div class="row">
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
          <div class="col-md-12">
            <input-text
              label="Latitude"
              name="latitude"
              v-model="form.lat"
              :error-text="errors.first('latitude')"
              disabled
            ></input-text>
          </div>
          <div class="col-md-12">
            <input-text
              label="Longitude"
              name="longitude"
              v-model="form.long"
              :error-text="errors.first('longitude')"
              disabled
            ></input-text>
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
  </div>
</div>
</template>

<script>
import Form from "@utils/Form";
import DeliveryJunction from "@utils/models/DeliveryJunction";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "DeliveryJunctionCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        location: "",
        lat: "",
        long: "",
      }),
      model: new DeliveryJunction(),
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
          name: `delivery-junction.index`,
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
        this.$router.push({
          name: `delivery-junction.index`,
        });
        alertMessage("Data updated successfully.");
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { location, lat, long } = data.data;

      this.form = new Form({
        location,
        lat,
        long,
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
    const myLatlng = { lat: 27.733697229707158, lng: 85.34125707301315 };
    const map = new google.maps.Map(document.getElementById("myMap"), {
      zoom: 14,
      center: myLatlng,
    });
    // Create the initial InfoWindow.
    let infoWindow = new google.maps.InfoWindow({
      content: "Click the map to get Lat/Lng!",
      position: myLatlng,
    });
    infoWindow.open(map);
    // Configure the click listener.
    map.addListener("click", (mapsMouseEvent) => {
      // Close the current InfoWindow.
      infoWindow.close();
      // Create a new InfoWindow.
      infoWindow = new google.maps.InfoWindow({
        position: mapsMouseEvent.latLng,
      });
      infoWindow.setContent(
        JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
      );
      this.form.lat = mapsMouseEvent.latLng.lat()
      this.form.long = mapsMouseEvent.latLng.lng()
      infoWindow.open(map);
    });
  },

  watch: {},
};
</script>
<style>
  .map-container{
    overflow:hidden;
    padding-bottom:56.25%;
    position:relative;
    height:0;
  }
  .map-container iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
  }
  .z-depth-1-half {
    -webkit-box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18),0 4px 15px 0 rgba(0,0,0,0.15) !important;
    box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18),0 4px 15px 0 rgba(0,0,0,0.15) !important;
  }
</style>
