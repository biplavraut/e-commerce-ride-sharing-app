<template>
  <app-card :title="edit ? 'Edit ' + form.title : 'Add New <b>Deal</b>'">
    <form @submit.prevent="saveData" @change="clearError">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            label=" Deal Info Image (450px * 90px)"
            title="450px * 90px"
            width="180"
            height="72"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Title"
                name="title"
                v-model="form.title"
                v-validate="'required'"
                required
                :error-text="errors.first('title')"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('title')"
                >* {{ form.errors.get("title") }}</small
              >
            </div>
            <div class="col-md-6">
              <input-text
                label="Subtitle"
                name="sub_title"
                v-model="form.sub_title"
                :error-text="errors.first('sub_title')"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('sub_title')"
                >* {{ form.errors.get("sub_title") }}</small
              >
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input-select
                v-model="form.categoryId"
                v-validate="'required'"
                name="service"
                label="Service*"
                :options="services"
                :selected="this.form.categoryId == services.id"
                required
              ></input-select>
            </div>
            <div class="col-md-6">
              <input-checkbox
                label="Enable?"
                name="status"
                v-model="form.status"
                :true-value="1"
                :false-value="0"
                :error-text="errors.first('status')"
              ></input-checkbox>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('status')"
                >* {{ form.errors.get("status") }}</small
              >
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Background Color"
                name="bg_color"
                v-model="form.bg_color"
                :error-text="errors.first('bg_color')"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('bg_color')"
                >* {{ form.errors.get("bg_color") }}</small
              >
            </div>
            <div class="col-md-6">
              <div class="color-picker" id="bg-color-picker"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Text Color"
                name="text_color"
                v-model="form.text_color"
                :error-text="errors.first('text_color')"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('text_color')"
                >* {{ form.errors.get("text_color") }}</small
              >
            </div>
            <div class="col-md-6">
              <div class="color-picker" id="text-color-picker"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="From Date"
                v-model="from_date"
                :error-text="errors.first('from')"
                datepicker
                required
                @input="checkFromDate()"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('from')"
                >* {{ form.errors.get("from") }}</small
              >
            </div>
            <div class="col-md-6">
              <input-text
                label="From Time"
                v-model="from_time"
                :error-text="errors.first('from')"
                timepicker
                required
                @input="checkFromDate()"
              ></input-text>
              <small
                class="text-center text-danger"
                v-if="form.errors.has('from')"
                >* {{ form.errors.get("from") }}</small
              >
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="To Date"
                v-model="to_date"
                :error-text="errors.first('to')"
                datepicker
                required
                @input="checkToDate()"
              ></input-text>
            </div>
            <div class="col-md-6">
              <input-text
                label="To Time"
                v-model="to_time"
                :error-text="errors.first('to')"
                timepicker
                required
                @input="checkToDate()"
              ></input-text>
            </div>
            <small class="text-center text-danger" v-if="form.errors.has('to')"
              >* {{ form.errors.get("to") }}</small
            >
          </div>

          <div class="row">
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
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Deal from "@utils/models/Deal";
import Service from "@utils/models/Category";

import { store, save } from "@utils/mixins/Crud";

export default {
  name: "DealCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        title: "",
        sub_title: "",
        image: "",
        categoryId: "",
        status: false,
        bg_color: "",
        text_color: "",
        from: "",
        to: "",
      }),
      from_date: "",
      from_time: "",
      to_date: "",
      to_time: "",
      model: new Deal(),
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
        this.$router.push({ name: `deal.index` });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        title,
        sub_title,
        image,
        categoryId,
        status,
        bg_color,
        text_color,
        from,
        to,
        from_date,
        from_time,
        to_date,
        to_time,
      } = data.data;
      this.form = new Form({
        title,
        sub_title,
        image: "",
        categoryId: categoryId,
        status: status,
        bg_color,
        text_color,
        from,
        to,
      });
      this.from_date = from_date;
      this.from_time = from_time;
      this.to_date = to_date;
      this.to_time = to_time;
      this.imageUrl = image;
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
    checkFromDate() {
      this.form.from = this.from_date + " " + this.from_time;
      this.clearError();
    },
    checkToDate() {
      this.form.to = this.to_date + " " + this.to_time;
      this.clearError();
    },
    clearError() {
      this.form.errors.errors = {};
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();
    }
    this.getServices();
    var swatches = [
      "rgba(244, 67, 54, 1)",
      "rgba(233, 30, 99, 1)",
      "rgba(156, 39, 176, 1)",
      "rgba(103, 58, 183, 1)",
      "rgba(63, 81, 181, 1)",
      "rgba(33, 150, 243, 1)",
      "rgba(3, 169, 244, 1)",
      "rgba(0, 188, 212, 1)",
      "rgba(0, 150, 136, 1)",
      "rgba(76, 175, 80, 1)",
      "rgba(139, 195, 74, 1)",
      "rgba(205, 220, 57, 1)",
      "rgba(255, 235, 59, 1)",
      "rgba(255, 193, 7, 1)",
    ];
    // Simple example, see optional options for more configuration.
    const bgPickr = Pickr.create({
      el: "#bg-color-picker",
      theme: "classic",
      default: "#f5e353",
      swatches: swatches,
      components: {
        // Main components
        preview: true,
        opacity: true,
        hue: true,
        // Input / output Options
        interaction: {
          hex: true,
          input: true,
          clear: true,
          save: true,
        },
      },
    }).on("save", (color) => {
      this.form.bg_color = color.toHEXA().toString();
    });
    const textPickr = Pickr.create({
      el: "#text-color-picker",
      theme: "classic",
      default: "#0779bd",
      swatches: swatches,
      components: {
        // Main components
        preview: true,
        opacity: true,
        hue: true,
        // Input / output Options
        interaction: {
          hex: true,
          input: true,
          clear: true,
          save: true,
        },
      },
    }).on("save", (color) => {
      this.form.text_color = color.toHEXA().toString();
    });
  },

  watch: {},
};
</script>
