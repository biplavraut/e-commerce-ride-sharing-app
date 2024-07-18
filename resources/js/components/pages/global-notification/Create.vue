<template>
  <app-card
    :title="edit ? 'Edit ' + form.title : 'Add New <b>Notification</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-12">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            label=" Image(Max : 2000px, Min : 300px | Ratio H1:W2)"
            :error-text="errors.first('image')"
            id="image"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-md-6">
          <input-text
            label="Title"
            name="title"
            v-validate="'required'"
            v-model="form.title"
            required
            :error-text="errors.first('title')"
          ></input-text>
        </div>

        <div class="col-md-6">
          <div class="form-group asdh-select" name="role">
            <label>Notification For</label>
            <select class="form-control" v-model="form.for">
              <option value disabled>Select Notification For</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in tos"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>
          <small class="text-center text-danger" v-if="errors.any('for')"
            >* {{ errors.first("for") }}</small
          >
        </div>

        <div class="col-md-12">
          <textarea
            label="Message"
            name="message"
            class="form-control"
            rows="5"
            v-model="form.message"
            placeholder="Write Message here......."
            
          ></textarea>
          <small class="text-center text-danger" v-if="errors.any('message')"
            >* {{ errors.first('message') }}</small
          >
        </div>

        <div class="col-md-12">
          <div class="col-md-10">
            <input-checkbox
              label="Use Coordinates"
              v-model="form.geo"
              v-if="
                form.for === 'rider' ||
                form.for === 'active-rider' ||
                form.for === 'inactive-rider'
              "
            ></input-checkbox>
          </div>

          <div class="col-md-2">
            <input-checkbox
              label="send as SMS"
              v-model="form.sms"
            ></input-checkbox>
          </div>

          <div class="col-md-4" v-if="form.geo">
            <input-text
              label="Latitude"
              name="lat"
              v-validate="'required'"
              v-model="form.lat"
              required
              :error-text="errors.first('lat')"
            ></input-text>
          </div>

          <div class="col-md-4" v-if="form.geo">
            <input-text
              label="Longitude"
              name="long"
              v-validate="'required'"
              v-model="form.long"
              required
              :error-text="errors.first('long')"
            ></input-text>
          </div>

          <div class="col-md-4" v-if="form.geo">
            <input-text
              label="Radius (in KM)"
              name="radius"
              type="number"
              v-validate="'required'"
              v-model="form.radius"
              required
              :error-text="errors.first('radius')"
            ></input-text>
          </div>

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
import GlobalNotification from "@utils/models/GlobalNotification";
import { store, save } from "@utils/mixins/Crud";
import Textarea from "../../material/input/Textarea.vue";

export default {
  components: { Textarea },
  name: "GlobalNotificationCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        title: "",
        message: "",
        for: "",
        image: "",
        geo: false,
        sms: false,
        lat: "",
        long: "",
        radius: 0,
      }),
      model: new GlobalNotification(),
      tos: [
        {
          id: "rider",
          name: "All Riders",
        },
        {
          id: "active-rider",
          name: "Active Riders",
        },
        {
          id: "inactive-rider",
          name: "Inactive Riders",
        },
        {
          id: "unverified-rider",
          name: "Unverified Riders",
        },
        {
          id: "verified-rider",
          name: "Verified Riders",
        },
        {
          id: "blocked-rider",
          name: "Blocked Riders",
        },
        {
          id: "blacklisted-rider",
          name: "Blacklisted Riders",
        },
        {
          id: "associated-rider",
          name: "Associated Riders",
        },
        {
          id: "incomplete-rider",
          name: "Incomplete Document Riders",
        },
        {
          id: "male-rider",
          name: "Male Riders",
        },
        {
          id: "female-rider",
          name: "Female Riders",
        },
        {
          id: "end-user",
          name: "App Users",
        },
        {
          id: "active-user",
          name: "Active App Users",
        },
        {
          id: "passive-user",
          name: "Inactive App Users",
        },
        {
          id: "blocked-user",
          name: "Blocked App Users",
        },
        {
          id: "elite-user",
          name: "Elite Users",
        },
        {
          id: "male-user",
          name: "Male App Users",
        },
        {
          id: "female-user",
          name: "Female App Users",
        },
        {
          id: "vendor",
          name: "Vendor",
        },
      ],
    };
  },

  methods: {
    async storeData() {
      if (confirm("Are you sure? You want to proceed.")) {
        this.form.errors.clear();

        try {
          await this.model.store(this.form.data());
          alertMessage("Data saved successfully.");
          this.model.cache.invalidate();

          this.$router.push({
            name: `global-notification.index`,
          });
        } catch (error) {
          this.form.errors.initialize(error.data.errors);
          if (this.form.errors.has("image")) Helpers.focusId("image");
        }
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
          name: `global-notification.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, message, to, image, geo, lat, long, radius, sms } =
        data.data;

      this.form = new Form({
        title,
        message,
        for: to,
        image: "",
        geo,
        lat,
        long,
        radius,
        sms,
      });

      this.tos = [
        {
          id: "rider",
          name: "All Riders",
        },
        {
          id: "active-rider",
          name: "Active Riders",
        },
        {
          id: "inactive-rider",
          name: "Inactive Riders",
        },
        {
          id: "unverified-rider",
          name: "Unverified Riders",
        },
        {
          id: "verified-rider",
          name: "Verified Riders",
        },
        {
          id: "blocked-rider",
          name: "Blocked Riders",
        },
        {
          id: "blacklisted-rider",
          name: "Blacklisted Riders",
        },
        {
          id: "associated-rider",
          name: "Associated Riders",
        },
        {
          id: "incomplete-rider",
          name: "Incomplete Document Riders",
        },
        {
          id: "male-rider",
          name: "Male Riders",
        },
        {
          id: "female-rider",
          name: "Female Riders",
        },
        {
          id: "end-user",
          name: "App Users",
        },
        {
          id: "active-user",
          name: "Active App Users",
        },
        {
          id: "passive-user",
          name: "Inactive App Users",
        },
        {
          id: "blocked-user",
          name: "Blocked App Users",
        },
        {
          id: "elite-user",
          name: "Elite Users",
        },
        {
          id: "male-user",
          name: "Male App Users",
        },
        {
          id: "female-user",
          name: "Female App Users",
        },
        {
          id: "vendor",
          name: "Vendor",
        },
      ];

      this.imageUrl = image;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
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
