<template>
  <app-card :title="edit?'Edit '+form.name:'Add New <b>News</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image v-model="form.image"
                       :image-url="imageUrl"
                       required
                       name="image"
                       v-validate="'required'"
                       :error-text="errors.first('image')"
                       id="image"
                       width="150"
                       height="150"></input-image>
          <small class="text-danger"
                 v-if="form.errors.has('image')">{{ form.errors.get('image') }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text label="Name"
                      name="name"
                      v-validate="'required'"
                      v-model="form.name"
                      required
                      :error-text="errors.first('name')"></input-text>
          <input-text label="Slug"
                      name="slug"
                      v-validate="'required'"
                      v-model="form.slug"
                      required
                      :error-text="errors.first('slug')"></input-text>

          <app-quill-editor label="Description"
                            name="description"
                            v-model="form.description"
                            v-validate="'required'"
                            :error-text="errors.first('description')"></app-quill-editor>

          <button type="submit"
                  :disabled="form.errors.any(errors.any())"
                  class="btn btn-success pull-right">{{ edit ? "Update" : "Save" }}
          </button>
        </div>
      </div>
    </form>
  </app-card>
</template>

<script>
  import Form from "@utils/Form";
  import News from "@utils/models/News";
  import {store, save} from "@utils/mixins/Crud";

  export default {
    name: "NewsCreate",

    mixins: [store, save],

    data() {
      return {
        edit: false,
        imageUrl: Helpers.cameraImage(),
        form: new Form({
          name: "",
          slug: "",
          image: "",
          description: ""
        }),
        model: new News()
      };
    },

    methods: {
       
      async updateData() {
        try {
          let data = await this.model.update(this.$route.params.id, this.form.data(true));
          this.imageUrl = data.data.image;
          this.model.cache.invalidate();
          alertMessage("Data updated successfully.");
        } catch (error) {
          alertMessage("The given data was invalid.", 'danger');
          this.form.errors.initialize(error.data.errors);
        }
      },

      async getData() {
        let data = await this.model.show(this.$route.params.id);
        let {name, slug, image150, description} = data.data;

        this.form = new Form({
          name,
          slug,
          image: "",
          description
        });

        this.imageUrl = image150;
      }
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

      'form.image': function (val) {
        let type = typeof val;
        if (type === 'object') {
          this.form.errors.clear('image');
        }
      }
    }
  };
</script>
