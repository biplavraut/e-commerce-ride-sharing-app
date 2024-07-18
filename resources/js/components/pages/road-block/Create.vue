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
            :error-text="errors.first('image')"
            id="image"
            width="250"
            height="150"
          ></input-image>
          <center>
            <span class="text text-muted center"
              >Best Ratio: 16:9 (912*512)</span
            >
          </center>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-md-6">
          <input-text
            label="Road Block Title"
            name="title"
            v-validate="'required'"
            v-model="form.title"
            required
            :error-text="errors.first('title')"
          ></input-text>
        </div>
        <div class="col-md-6">
          <div class="form-group asdh-select" name="role">
            <label>Message For</label>
            <select class="form-control" v-model="form.type">
              <option value disabled>Select Message For</option>
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
          <small class="text-center text-danger" v-if="errors.any('for')"
            >* {{ errors.first("for") }}</small
          >
        </div>

        <div class="col-md-12">
          <textarea
            label="Descriptoin"
            name="description"
            class="form-control"
            rows="5"
            v-model="form.description"
            placeholder="Description text goes here"
          ></textarea>
        </div>

        <div class="col-md-6">
          <input-checkbox
            label="Road Block Status"
            v-model="form.status"
          ></input-checkbox>
        </div>

        <div class="col-md-6">
          <input-checkbox
            label="Show image on top"
            v-model="form.showImageOnTop"
          ></input-checkbox>
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
import RoadBlockNotification from "@utils/models/RoadBlockNotification";
import { store, save } from "@utils/mixins/Crud";
import Textarea from "../../material/input/Textarea.vue";

export default {
  components: { Textarea },
  name: "RoadBlockNotificationCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        title: "",
        description: "",
        image: "",
        type: "",
        showImageOnTop: false,
        status: false,
      }),
      model: new RoadBlockNotification(),
      types: [
        { id: "user", name: "For User App" },
        { id: "rider", name: "For Rider App" },
        { id: "vendor", name: "For Vendor App" },
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
        this.$router.push({
          name: `road-block.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, description, image, showImageOnTop, type, status } =
        data.data;

      this.form = new Form({
        title,
        description,
        image: "",
        showImageOnTop,
        type,
        status,
      });

      this.types = [
        { id: "user", name: "For User App" },
        { id: "rider", name: "For Rider App" },
        { id: "vendor", name: "For Vendor App" },
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
