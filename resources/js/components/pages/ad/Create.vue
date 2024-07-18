<template>
  <app-card :title="edit ? 'Edit ' + form.title : 'Add New <b>Ad</b>'">
    <template slot="actions">
      <app-btn-link route-name="ad.create">Add New</app-btn-link>
    </template>
    <form @submit.prevent="saveData">
      <div class="row">
        <!-- <div class="col-sm-3 col-md-2">
          
        </div> -->

        <div class="col-sm-12 col-md-12 container">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            :label="'Image (728px * 90px)*'"
            title="728px * 90px"
            width="728"
            height="90"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
          <input-text
            label="Title"
            name="title"
            v-validate="'required'"
            v-model="form.title"
            required
            :error-text="errors.first('title')"
          ></input-text>
          <input-text
            label="URL"
            name="url"
            v-model="form.url"
            :error-text="errors.first('url')"
          ></input-text>

          <div class="form-group asdh-select" name="role">
            <label>Ads For</label>
            <select class="form-control" v-model="form.type" v-validate="'required'"
            required>
              <option value disabled>Select Ads For</option>
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
          <div class="col-md-12">
            <input-select
              v-model="form.serviceId"
              name="service"
              label="Service"
              :options="services"
            ></input-select>
          </div>
          <small class="text-center text-danger" v-if="errors.any('for')"
            >* {{ errors.first("for") }}</small
          >

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
import Ad from "@utils/models/Ad";
import Service from "@utils/models/Category";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "AdCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        title: "",
        image: "",
        type: "",
        serviceId: 0,
        url: "",
        hide: false,
      }),
      model: new Ad(),
      types: [
        { id: "user", name: "For User App" },
        { id: "rider", name: "For Rider App" },
        { id: "vendor", name: "For Vendor App" },
        { id: "utility", name: "For Utility Service" },
        { id: "checkout", name: "For Checkout" }
      ],
      service: new Service(),
      services: [],
    };
  },

  methods: {
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image;
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `ad.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },
    async getServices() {
      let services = await this.service.getAll();
      this.services = services.data.map((item) => {
        return {
          id: item.id,
          name: item.name,
        };
      });
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, image, url, type, serviceId, hide } = data.data;

      this.form = new Form({
        title,
        image: "",
        url,
        hide,
        type,
        serviceId: serviceId,
      });

      this.types = [
        { id: "user", name: "For User App" },
        { id: "rider", name: "For Rider App" },
        { id: "vendor", name: "For Vendor App" },
        { id: "utility", name: "For Utility Service" },
        { id: "checkout", name: "For Checkout" }
      ];
      this.imageUrl = image;
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    this.getServices();
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
