<template>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>FAQ</b>'" >
  <template slot="actions">
      <app-btn-link route-name="faq.index">List FAQ</app-btn-link>
    </template>
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-12">
          <input-text
            label="Faq title"
            type="text"
            name="faqTitle"
            v-model="form.faqTitle"
            v-validate="'required'"
            :error-text="errors.first('faqTitle')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('faqTitle')"
            >{{ form.errors.get("faqTitle") }}
          </small>
        </div>
        <div class="col-md-12">
          <input-text
            label="FAQ Description"
            type="text"
            name="faqDescription"
            v-model="form.faqDescription"
            v-validate="'required'"
            :error-text="errors.first('faqDescription')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('faqDescription')"
            >{{ form.errors.get("faqDescription") }}
          </small>
        </div>
        <div class="col-md-12">
          <input-text
            label="Order"
            type="number"
            name="  "
            v-model="form.order"
            v-validate="'required'"
            :error-text="errors.first('order')"
            required
          ></input-text>
          <small class="text-danger" v-if="form.errors.has('order')"
            >{{ form.errors.get("order") }}
          </small>
        </div>
        <div class="col-md-12">
          <button
            type="submit"
            class="btn btn-success pull-right"
          >
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
import Faq from "@utils/models/Faq";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "FAQCreate",
  mixins: [store, save],
  data() {
    return {
      edit: false,
      form: new Form({
        faqTitle: "",
        faqDescription: "",
        order: "",
      }),
      model: new Faq(),
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
          name: `faq.index`,
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
          name: `faq.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },
    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { faqTitle,faqDescription,order } = data.data;
      this.form = new Form({
            faqTitle,
            faqDescription,
            order,
      });
    },
  },
  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");
    console.log(this.edit+" value");
    if (this.edit) {
      this.getData();
    }
  },
  watch: {},
};
</script>
