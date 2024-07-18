<template>
  <app-card :title="edit ? 'Edit ' + form.name : 'Add New <b>Branch</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
          <div class="col-sm-3 col-md-2">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            :label="'Image (720px * 440px)*'"
            title="720px * 440px"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>
        <div class="col-sm-9 col-md-10">
          <input-select
            v-model="form.vendorId"
            label="Vendor"
            :options="vendors"
          ></input-select>

          <input-text
            label="Name"
            name="name"
            v-validate="'required'"
            v-model="form.name"
            required
            :error-text="errors.first('name')"
          ></input-text>

          <input-text
            label="Expire In"
            name="expire_in"
            type="datetime-local"
            v-model="form.expireIn"
            :error-text="errors.first('expire_in')"
          ></input-text>

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
import Partner from "@utils/models/Partner";
import { store, save } from "@utils/mixins/Crud";
import Vendor from "@utils/models/Vendor";

export default {
  name: "PartnerCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      parent: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        name: "",
        image: "",
        vendorId: "",
        expireIn: "",
        hide: false,
        parent_id: this.$route.params.parentId,
      }),
      model: new Partner(),
      vendor: new Vendor(),
      vendors: [],
      branches: []
    };
  },

  methods: {
    async storeData() {
        this.form.errors.clear();
        try {
            await this.model.store(this.form.data());
            alertMessage("Data saved successfully.");
            this.model.cache.invalidate();
            this.$router.push({ name: 'partner.listbranch', params:{parentId:this.$route.params.parentId} });
        } catch (error) {
            this.form.errors.initialize(error.data.errors);
            if (this.form.errors.has("image")) Helpers.focusId("image");
            if (this.form.errors.has("icon")) Helpers.focusId("icon");
        }
    },
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image;
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, image, expireIn, hide, vendor } = data.data;

      this.form = new Form({
        name,
        image: "",
        expireIn,
        vendorId: vendor.id,
        hide,
      });

      this.imageUrl = image;
    },
    async getVendorList() {
      let vendors = await this.vendor.getList();
      this.vendors = vendors.data.map((vendor) => {
        return {
          id: vendor.id,
          name: vendor.businessName,
        };
      });
    },

    getExistingBranches(){
        axios
        .get("/admin/partner/branches/" + this.$route.params.parentId)
        .then((data) => (this.branches = data.data));
    }
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    this.getVendorList();

    if (this.edit) {
      this.imageUrl = Helpers.loadingImage();
      this.getData();
    }

    this.parent = this.$route.params.hasOwnProperty("parentId");

    this.getExistingBranches();
    
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
