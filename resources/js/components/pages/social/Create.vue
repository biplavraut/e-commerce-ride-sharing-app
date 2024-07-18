<template>
  <app-card :title="edit?'Edit '+form.name:'Add New <b>Social</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image v-model="form.icon"
                       :image-url="iconUrl"
                       label="Icon"
                       name="icon"
                       v-validate="'required'"
                       :error-text="errors.first('icon')"
                       id="icon"
                       width="150"
                       height="150"
                       required></input-image>
          <small class="text-danger"
                 v-if="form.errors.has('icon')">* {{ form.errors.get('icon') }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text label="Name"
                      name="name"
                      v-model="form.name"
                      v-validate="'required'"
                      :error-text="errors.first('name')"
                      required></input-text>

          <input-text label="Url"
                      name="url"
                      v-model="form.url"
                      v-validate="'required|url'"
                      :error-text="errors.first('url')"
                      required></input-text>

          <button type="submit"
                  :disabled="this.form.errors.any(errors.any())"
                  class="btn btn-success pull-right">{{ edit ? "Update" : "Save" }}
          </button>
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
  import Form from "@utils/Form";
  import Social from "@utils/models/Social";
  import {store, save} from "@utils/mixins/Crud";

  export default {
    name: "SocialCreate",

    mixins: [store, save],

    data() {
      return {
        form: new Form({
          name: "",
          icon: "",
          url: ""
        }),
        edit: false,
        iconUrl: Helpers.cameraImage(),
        model: new Social()
      };
    },

    methods: {
      async updateData() {
        try {
          let data = await this.model.update(this.$route.params.id, this.form.data(true));
          this.iconUrl = data.data.icon;
          this.model.cache.invalidate();
          alertMessage("Data updated successfully.");
        } catch (error) {
          alertMessage("The given data was invalid.", 'danger');
          this.form.errors.initialize(error.data.errors);
        }
      },

      async getData() {
        let data = await this.model.show(this.$route.params.id);
        let {name, url, icon150} = data.data;

        this.form = new Form({
          name,
          url,
          icon: ""
        });

        this.iconUrl = icon150;
      },
    },

    mounted() {
      this.edit = this.$route.params.hasOwnProperty("id");

      if (this.edit) {
        this.iconUrl = Helpers.loadingImage();
        this.getData();
      }
    },

    watch: {
      'form.icon': function (val) {
        let type = typeof val;
        if (type === 'object') {
          this.form.errors.clear('icon');
        }
      }
    }
  };
</script>
