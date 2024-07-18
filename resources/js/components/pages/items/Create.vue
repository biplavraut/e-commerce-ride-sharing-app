<template>
  <app-card :title="edit ? 'Modify <b>Send Item</b>' : 'Add <b>SendItem</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-6">
          <input-text
            label="Send Item Name"
            type="text"
            name="name"
            v-model="form.name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('name')"
            >{{ form.errors.get("name") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="Flat Price"
            type="number"
            name="flatPrice"
            v-model="form.flatPrice"
            v-validate="'required'"
            :error-text="errors.first('flatPrice')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('flatPrice')"
            >{{ form.errors.get("flatPrice") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="Added price per kilometer"
            type="number"
            name="addedPerKmPrice"
            v-model="form.addedPerKmPrice"
            :error-text="errors.first('added_per_km_price')"
          ></input-text>
          <small
            class="text-danger"
            v-if="form.errors.has('added_per_km_price')"
            >{{ form.errors.get("added_per_km_price") }}
          </small>
        </div>
        <div class="col-md-6">
          <input-text
            label="Added price per Kilogram"
            type="number"
            name="addedWeightpricePerKg"
            v-model="form.addedWeightpricePerKg"
            :error-text="errors.first('added_weightprice_per_kg')"
          ></input-text>
          <small
            class="text-danger"
            v-if="form.errors.has('added_weightprice_per_kg')"
            >{{ form.errors.get("added_weightprice_per_kg") }}
          </small>
        </div>
        <div class="col-md-12">
          <input-checkbox label="Status" v-model="form.status"></input-checkbox>
          <small class="text-danger" v-if="form.errors.has('status')"
            >{{ form.errors.get("status") }}
          </small>
        </div>
        <div class="col-md-12">
          <button type="submit" class="btn btn-success pull-right">
            {{ edit ? "Update" : "Save" }}
          </button>
          <br />
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import Items from "@utils/models/Items";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "ItemsCreate",
  mixins: [store, save],
  data() {
    return {
      edit: false,
      form: new Form({
        name: "",
        flatPrice: "",
        addedPerKmPrice: "",
        addedWeightpricePerKg: "",
        status: false,
      }),
      model: new Items(),
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
          name: `items.index`,
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
        this.$router.push({
          name: `items.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
        alertMessage("The given data was invalid.", "danger");
      }
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let {
        name,
        flatPrice,
        addedPerKmPrice,
        addedWeightpricePerKg,
        status,
      } = data.data;
      this.form = new Form({
        name,
        flatPrice,
        addedPerKmPrice,
        addedWeightpricePerKg,
        status,
      });
    },
  },
  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    console.log(this.edit + " value");
    if (this.edit) {
      this.getData();
    }
  },
  watch: {},
};
</script>
