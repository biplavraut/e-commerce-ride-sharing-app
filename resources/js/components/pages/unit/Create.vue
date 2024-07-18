<template>
  <app-card :title="edit ? 'Edit ' + form.name : 'Add New <b>Unit</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-12">
          <input-select
            v-model="form.productCategoryId"
            name="category"
            label="Category"
            @input="getCategories(form.productCategoryId)"
            :options="categories"
          ></input-select>
          <small class="text-danger" v-if="form.errors.has('category')"
            >* {{ form.errors.get("category") }}</small
          >
        </div>
      </div>

      <div class="row" v-if="!edit">
        <div class="col-md-10">
          <h3 class="my-sub-heading">Units</h3>
        </div>
      </div>

      <div v-for="(unit, index) in form.units" :key="index">
        <div class="row" v-if="!edit">
          <div class="col-md-11">
            <input-text
              label="Name"
              type="text"
              :name="'name' + index"
              v-validate="'required'"
              v-model="unit.name"
              required
              :error-text="errors.first('name' + index)"
            ></input-text>
          </div>
          <div class="col-md-1 text-center" v-if="form.units.length > 1">
            <a
              href="#"
              title="delete"
              v-on:click="removeUnit()"
              class="btn btn-danger btn-sm"
              style="margin-top:40px;"
            >
              <span class="material-icons">delete</span>
            </a>
          </div>
        </div>
      </div>
      <div class="row" v-if="!edit">
        <div class="col-md-2">
          <button
            class="btn btn-warning btn-sm"
            title="add"
            type="button"
            v-on:click="addUnit()"
          >
            <i class="material-icons">add</i>
            <div class="ripple-container"></div>
          </button>
        </div>
      </div>

      <div class="row" v-if="edit">
        <div class="col-md-12">
          <input-text
            label="Name"
            type="text"
            name="name"
            v-validate="'required'"
            v-model="form.name"
            v
            required
            :error-text="errors.first('name')"
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
import ProductCategory from "@utils/models/ProductCategory";
import Unit from "@utils/models/Unit";
import { store, save } from "@utils/mixins/Crud";
import Error from "@utils/Error";

export default {
  name: "UnitCreate",

  mixins: [store, save],

  data() {
    return {
      form: new Form({
        productCategoryId: "",
        units: [{ id: 0, name: "" }],
        name: "",
      }),
      edit: false,
      model: new Unit(),
      category: new ProductCategory(),
      categories: [],
    };
  },

  methods: {
    addUnit() {
      this.form.units.push({
        id: 0,
        name: "",
      });
    },
    removeUnit() {
      if (this.form.units.length > 0) {
        this.form.units.pop();
      }
    },

    async storeData() {
      this.form.errors.clear();
      if (!this.form.productCategoryId) {
        alertMessage("Please choose category.", "danger");
        return;
      }

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `unit.index`,
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
      let { name, category } = data.data;

      this.form = new Form({
        name,
        productCategoryId: category.id,
      });
    },
    async getCategories() {
      let categories = await this.category.getAll();
      this.categories = categories.data.map((category) => {
        return {
          id: category.id,
          name: category.name,
        };
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getCategories();

    if (this.edit) {
      this.form.units.pop();
      this.getData();
    }
  },

  watch: {},
};
</script>
