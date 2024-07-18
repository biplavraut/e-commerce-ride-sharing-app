<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Layout Manager </b>'"
  >
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-4">
          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            v-model="form.name"
            required
            :error-text="errors.first('name')"
          ></input-text>
        </div>

        <div class="col-md-4">
          <input-select
            v-model="form.serviceId"
            name="service"
            label="Service"
            :options="services"
          ></input-select>
        </div>
        <div class="col-md-4">
          <input-select
            v-model="form.modelType"
            name="model_name"
            label="Model"
            :options="modelList"
            @change="getModelIds"
          ></input-select>
        </div>
      </div>
      <div class="row" v-if="modelIds.length > 0">
        <div class="col-md-12">
          <div class="row" style="margin-top: 5px">
            <div class="col-md-10">
              <h3 class="my-sub-heading">Select Multiple Item</h3>
            </div>
          </div>
          <div class="col-md-3" v-for="(item, index) in modelIds" :key="index">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input
                    type="checkbox"
                    v-model="form.modelId"
                    :value="item.id"
                    :disabled="item.hide==1"
                  />
                  {{ item.name }}
                  <small v-if="item.hide==1">(<i>is Hidden</i>)</small>
                </label>
              </div>
            </div>
          </div>
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
import LayoutManager from "@utils/models/LayoutManager";
import Service from "@utils/models/Category";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "LayoutManagerCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        name: "",
        modelType: "",
        modelId: [],
        serviceId: "",
      }),
      model: new LayoutManager(),
      service: new Service(),
      services: [],
      modelList: [],
      modelIds: [],
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();

      if (!this.form.serviceId) {
        alertMessage("Please select service.", "danger");
        return;
      }

      if (!this.form.modelType) {
        alertMessage("Please select model.", "danger");
        return;
      }

      if (this.form.modelId.length < 1) {
        alertMessage("Please select items to display.", "danger");
        return;
      }

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `layout-manager.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },

    async updateData() {
      if (!this.form.serviceId) {
        alertMessage("Please select service.", "danger");
        return;
      }

      if (!this.form.modelType) {
        alertMessage("Please select model.", "danger");
        return;
      }

      if (this.form.modelId.length < 1) {
        alertMessage("Please select items to display.", "danger");
        return;
      }

      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({
          name: `layout-manager.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, modelType, modelId, service } = data.data;

      this.form = new Form({
        name,
        modelType,
        modelId,
        serviceId: service.id,
      });
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
    async getModelList() {
      let lists = await this.model.getModelList();
      this.modelList = lists.data.map((item) => {
        return {
          id: item.value,
          name: item.name,
        };
      });
    },
    async getModelIds() {
      let ids = await this.model.getModelIdList(
        this.form.modelType,
        this.form.serviceId
      );
      this.modelIds = ids.map((item) => {
        return {
          id: item.id,
          name:
            this.form.modelType === "App\\GogoAd"
              ? "Name: " + item.name
              : "Name: " + item.name + " | Service: " + item.service,
          hide: item.hide
        };
      });
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.$route.params.data && !this.edit) {
      this.form.serviceId = this.$route.params.data;
    }

    if (this.edit) {
      this.getData();
    }

    this.getServices();

    this.getModelList();
  },

  watch: {
    "form.modelType": function (val) {
      this.getModelIds();
    },
    "form.serviceId": function (val) {
      if (this.form.modelType) {
        this.getModelIds();
      }
    },
  },
};
</script>
