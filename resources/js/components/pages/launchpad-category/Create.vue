<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Launchpad Category</b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-12">
          <input-text
            label="Name"
            name="name"
            v-model="form.name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            required
          ></input-text>

          <app-quill-editor
            label="Description"
            name="description"
            v-model="form.description"
            :error-text="errors.first('description')"
          ></app-quill-editor>

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
import LaunchpadCategory from "@utils/models/LaunchpadCategory";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "LaunchpadCategoryCreate",

  mixins: [store, save],

  data() {
    return {
      form: new Form({
        name: "",
        description: "",
      }),
      edit: false,
      model: new LaunchpadCategory(),
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
          name: "launchpad-category.index",
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
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, description } = data.data;

      this.form = new Form({
        name,
        description,
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },

  watch: {},
};
</script>
