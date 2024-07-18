<template>
<div class="row">
  <div class="col-md-7">
    <div id="myMap" class="z-depth-1-half map-container" style="height: 500px"></div>
  </div>
  <div class="col-md-5">
    <app-card :title="edit ? 'Edit ' + form.title : 'Add New <b>Hospital</b>'">
      <form @submit.prevent="saveData">
        <div class="row">
          <div class="col-md-12">
            <input-text
              label="Hospital Name"
              name="name"
              v-validate="'required'"
              v-model="form.title"
              required
              :error-text="errors.first('title')"
            ></input-text>
            <small
                class="text-center text-danger"
                v-if="form.errors.has('title')"
                >* {{ form.errors.get("title") }}</small
              >
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
          <div class="col-md-12">
            <label for="">Vendors</label>
            <multiselect v-model="form.vendors"
                    tag-placeholder="Vendors"
                    placeholder="Search &amp; select vendors"
                    label="name"
                    track-by="id"
                    :options="vendors"
                    :multiple="true"
                    :hide-selected="true"
                    :searchable="true"
                    :preserve-search="true"
                    :taggable="true"
                      ></multiselect>
          </div>

          <div class="col-md-6">
            <!-- :disabled="form.errors.any(errors.any())" -->
            <button
              type="submit"              
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
import Hospital from "@utils/models/Hospital";
import Vendor from "@utils/models/Vendor";
import { store, save } from "@utils/mixins/Crud";
import Multiselect from 'vue-multiselect'
// register globally

export default {
  name: "Hospital",

  mixins: [store, save],

  components: {
    Multiselect
  },

  data() {
    return {
      edit: false,
      form: new Form({
        location: "",
        lat: "",
        long: "",
        vendors:[]
      }),
      vendor: new Vendor(),
      vendors: [],
      model: new Hospital(),
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
          name: `hospital.index`,
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
          name: `hospital.index`,
        });
        alertMessage("Data updated successfully.");
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getVendorList() {
      let vendors = await this.vendor.getHealthList();
      this.vendors = vendors.data.map((vendor) => {
        return {
          id: vendor.id.toString(),
          name: vendor.businessName,
        };
      });
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, lat, long, vendors } = data.data;

      this.form = new Form({
        title,
        lat,
        long,
        vendors
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    this.getVendorList();
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
