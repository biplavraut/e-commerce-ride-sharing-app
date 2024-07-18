<template>
  <form @submit.prevent="updateSettings">
    <app-card title="Edit <b>TOR</b>">
      <div class="row">
        <div class="col-md-12">
          <app-quill-editor
            label="User's Terms &amp; Condition"
            name="user_tac"
            :error-text="errors.first('user_tac')"
            v-model="form.userTac"
          ></app-quill-editor>
        </div>
        <div class="col-md-12">
          <app-quill-editor
            label="Rider's Terms &amp; Condition"
            name="rider_tac"
            :error-text="errors.first('rider_tac')"
            v-model="form.riderTac"
          ></app-quill-editor>
        </div>
        <div class="col-md-12">
          <app-quill-editor
            label="Vendor's Terms &amp; Condition"
            name="vendor_tac"
            :error-text="errors.first('vendor_tac')"
            v-model="form.vendorTac"
          ></app-quill-editor>
        </div>
      </div>

      <div class="text-right">
        <button
          type="submit"
          :disabled="form.errors.any(errors.any())"
          class="btn btn-success"
        >
          Save
        </button>
      </div>
    </app-card>
  </form>
</template>

<script>
import { UPDATE_SETTINGS_URL } from "@routes/admin";
import { mapGetters } from "vuex";
import Form from "@utils/Form";

export default {
  name: "Tor",

  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        phone: "",
        establishedDate: "",
        address: "",
        about: "",
        riderTac: "",
        userTac: "",
        vendorTac: "",
        logo: "",
      }),
      logoUrl: "/images/camera.png",
    };
  },

  methods: {
    updateSettings() {
      this.$validator.validate().then((result) => {
        if (result) {
          this.form
            .put(UPDATE_SETTINGS_URL)
            .then((data) => {
              this.$store.commit("setSettings", data.data);
              this.logoUrl = this.settings.logo;
              this.form.logo = "";
              alertMessage("TOR successfully saved.");
            })
            .catch((error) => {
              switch (error.status) {
                case 422:
                  this.form.errors.initialize(error.data.errors);
                  if (this.form.errors.has("logo"))
                    Helpers.focusId("settings-logo");
                  break;
                default:
                  alertMessage(error.data.message, "danger");
                  break;
              }
            });
        } else {
          Helpers.focusFirstError(this.errors);
        }
      });
    },
  },

  computed: {
    ...mapGetters(["settings"]),
  },

  mounted() {
    this.form = new Form(this.settings);
    this.form.logo = "";
    this.logoUrl = this.settings.logo;
  },

  watch: {
    "form.logo": function (val) {
      this.form.errors.clear("logo");
    },
  },
};
</script>
