<template>
  <app-card :title="edit?'Edit '+form.name:'Add New <b>Testimonial</b>'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image v-model="form.image"
                       :image-url="imageUrl"
                       name="image"
                       v-validate="edit?'':'required'"
                       :error-text="errors.first('image')"
                       id="image"
                       width="150"
                       height="150"
                       required></input-image>
          <small class="text-center text-danger"
                 v-if="form.errors.has('image')">* {{ form.errors.get('image') }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text label="Name"
                      name="name"
                      v-model="form.name"
                      v-validate="'required'"
                      :error-text="errors.first('name')"
                      required></input-text>

          <input-textarea label="Message"
                          name="message"
                          v-model="form.message"
                          v-validate="'required'"
                          id="message"
                          placeholder="Enter message here..."
                          rows="5"
                          :error-text="errors.first('message')"
                          required></input-textarea>

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
  import Testimonial from "@utils/models/Testimonial";
  import {store, save} from "@utils/mixins/Crud";

  export default {
    name: "TestimonialCreate",

    mixins: [store, save],

    data() {
      return {
        form: new Form({
          name: "",
          image: "",
          message: ""
        }),
        edit: false,
        imageUrl: Helpers.cameraImage(),
        model: new Testimonial()
      };
    },

    methods: {
      async updateData() {
        try {
          let data = await this.model.update(this.$route.params.id, this.form.data(true));
          this.imageUrl = data.data.image150;
          this.model.cache.invalidate();
          alertMessage("Data updated successfully.");
        } catch (error) {
          alertMessage("The given data was invalid.", 'danger');
          this.form.errors.initialize(error.data.errors);
        }
      },

      async getData() {
        let data = await this.model.show(this.$route.params.id);
        let {name, message, image150} = data.data;
        this.form = new Form({
          name,
          message,
          image: ""
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
      'form.image': function (val) {
        let type = typeof val;
        if (type === 'object') {
          this.form.errors.clear('image');
        }
      }
    }
  };
</script>
