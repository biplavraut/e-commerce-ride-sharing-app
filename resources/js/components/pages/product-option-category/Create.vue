<template>
  <app-card
    :title="edit ? 'Edit ' + form.title : 'Add New <b>Option Category</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
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
          <input-text
            label="Slug"
            name="slug"
            v-validate="'required'"
            v-model="form.slug"
            required
            :error-text="errors.first('slug')"
          ></input-text>
        </div>

        <div class="col-md-6">
          <div class="form-group asdh-select" name="role">
            <label>Layout Type</label>
            <select class="form-control" v-model="form.layout">
              <option value disabled>Select Layout Type</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in layouts"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <input-select
            v-model="form.serviceId"
            name="service"
            label="Service"
            :options="services"
          ></input-select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <input-text
            label="Title Color (in HEX)"
            name="title_color"
            v-model="form.titleColor"
            :error-text="errors.first('title_color')"
          ></input-text>
        </div>

        <div class="col-md-6">
          <input-text
            label="Layout Color (in HEX)"
            name="layout_color"
            v-model="form.layoutColor"
            :error-text="errors.first('layout_color')"
          ></input-text>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
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
import ProductOptionCategory from "@utils/models/ProductOptionCategory";
import { store, save } from "@utils/mixins/Crud";
import Service from "@utils/models/Category";

export default {
  name: "ProductOptionCategoryCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        title: "",
        slug: "",
        serviceId: "",
        layout: "",
        titleColor: "",
        layoutColor: "",
      }),
      model: new ProductOptionCategory(),
      layouts: [
        {
          id: 1,
          name: "Layout 1",
        },
        {
          id: 2,
          name: "Layout 2 (Horizontal)",
        },
        {
          id: 3,
          name: "Layout 3",
        },
        {
          id: 4,
          name: "Layout 4",
        },
        {
          id: 5,
          name: "Layout 5",
        },
        {
          id: 6,
          name: "Vendor Layout",
        },
        {
          id: 7,
          name: "Deal of the day",
        },
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
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `product-option-category.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, slug, layout, service, titleColor, layoutColor } = data.data;

      this.form = new Form({
        title,
        slug,
        layout,
        serviceId: service.id,
        titleColor,
        layoutColor,
      });
      this.layouts = [
        {
          id: 1,
          name: "Layout 1",
        },
        {
          id: 2,
          name: "Layout 2 (Horizontal)",
        },
        {
          id: 3,
          name: "Layout 3",
        },
        {
          id: 4,
          name: "Layout 4",
        },
        {
          id: 5,
          name: "Layout 5",
        },
        {
          id: 6,
          name: "Vendor Layout",
        },
      ];
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
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getServices();

    if (this.$route.params.data && !this.edit) {
      this.form.serviceId = this.$route.params.data;
    }

    if (this.edit) {
      this.getData();
    }
  },

  watch: {
    "form.title": function (val) {
      if (!this.edit) {
        this.form.slug = val.slug();
      }
    },
  },
};
</script>
