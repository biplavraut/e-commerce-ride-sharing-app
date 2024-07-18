<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Utility Service</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            width="150"
            height="150"
          ></input-image>
          <small
            class="text-center text-danger"
            v-if="serverErrors.get('image')"
            >* {{ serverErrors.get("image") }}</small
          >
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            v-model="form.name"
            placeholder="Enter Service Name"
            required
          ></input-text>

          <input-text
            label="Slug"
            name="slug"
            v-validate="'required'"
            :error-text="errors.first('slug') || serverErrors.get('slug')"
            v-model="form.slug"
            required
          ></input-text>
          <small class="text-center text-danger" v-if="form.errors.has('slug')"
            >* {{ form.errors.get("slug") }}</small
          >

          <input-text
            label="Subtitle"
            name="subtitle"
            :error-text="errors.first('subtitle')"
            v-model="form.subtitle"
            placeholder="Enter Subtitle"
          ></input-text>

          <input-text
            label="Cashback (%)"
            name="cashback"
            :error-text="errors.first('cashback')"
            v-model="form.cashback"
          ></input-text>

          <div class="row">
            <div class="col-md-3">
              <input-checkbox
                label="Enable Service"
                v-model="form.enabled"
              ></input-checkbox>
            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3">
              <input-checkbox
                label="Enable Promo"
                v-model="form.enabled_promo"
              ></input-checkbox>
            </div>
          </div>
        </div>
      </div>

      <div class="text-right">
        <button
          type="submit"
          class="btn btn-success btn-fill"
          :disabled="errors.any()"
          v-text="edit ? 'Update' : 'Save'"
        ></button>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import AddService from "@utils/models/AddService";
import { store, save } from "@utils/mixins/Crud";
import Error from "@utils/Error";

export default {
  name: "AdditionServiceCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        name: "",
        image: "",
        enabled: true,
        enabled_promo: false,
        cashback: 0,
        subtitle: "",
        slug: "",
      }),
      model: new AddService(),
      serverErrors: new Error(),
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
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, image, enabled, enabledPromo, cashback, subtitle, slug } = data.data;

      this.form = new Form({
        name,
        image: "",
        enabled: enabled,
        enabled_promo:enabledPromo,
        cashback,
        subtitle,
        slug,
      });
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
    "form.name": function (val) {
      if (!this.edit) {
        this.form.slug = val.slug();
      }
    },
  },
};
</script>
