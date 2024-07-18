<template>
  <app-card
    :title="edit ? 'Edit ' + form.title : 'Add New <b>Academy Content</b>'"
  >
    <template slot="actions">
      <app-btn-link route-name="academy-content.create">Add New</app-btn-link>
    </template>
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <input-image
            v-model="form.image"
            :image-url="imageUrl"
            name="image"
            :error-text="errors.first('image')"
            id="image"
            label=" Image (500px * 375px) *"
            title="500px * 375px"
            width="150"
            height="150"
          ></input-image>
          <small class="text-danger" v-if="form.errors.has('image')"
            >{{ form.errors.get("image") }}
          </small>
        </div>

        <div class="col-sm-9 col-md-10">
          <input-text
            label="Title"
            name="title"
            v-validate="'required'"
            v-model="form.title"
            required
            :error-text="errors.first('title')"
          ></input-text>

          <div class="form-group asdh-select" name="role">
            <label>Content For</label>
            <select class="form-control" v-model="form.fors">
              <option value disabled>Select Content For</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in fors"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>

          <input-text
            label="YouTube Video URL"
            name="video_url"
            v-model="form.videoUrl"
            :error-text="errors.first('video_url')"
          ></input-text>

          <input-text
            label="External URL"
            name="url"
            v-model="form.url"
            :error-text="errors.first('url')"
          ></input-text>

          <app-quill-editor
            label="Description"
            name="description"
            :error-text="errors.first('description')"
            v-model="form.description"
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
import AcademyContent from "@utils/models/AcademyContent";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "AcademyContentCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      imageUrl: Helpers.cameraImage(),
      form: new Form({
        title: "",
        image: "",
        url: "",
        fors: "user",
        videoUrl: "",
        description: "",
      }),
      model: new AcademyContent(),
      fors: [
        {
          id: "user",
          name: "User",
        },
        {
          id: "rider",
          name: "Rider",
        },
        {
          id: "vendor",
          name: "Vendor",
        },
      ],
    };
  },

  methods: {
    async updateData() {
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.imageUrl = data.data.image;
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");
        this.$router.push({ name: `academy-content.index` });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { title, image, url, fors, description, videoUrl } = data.data;

      this.form = new Form({
        title,
        image: "",
        url,
        fors,
        description,
        videoUrl,
      });

      this.imageUrl = image;

      this.fors = [
        {
          id: "user",
          name: "User",
        },
        {
          id: "rider",
          name: "Rider",
        },
        {
          id: "vendor",
          name: "Vendor",
        },
      ];
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
    "form.image": function (val) {
      let type = typeof val;
      if (type === "object") {
        this.form.errors.clear("image");
      }
    },
  },
};
</script>
