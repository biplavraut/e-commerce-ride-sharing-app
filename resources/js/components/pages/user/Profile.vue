<template>
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <app-card title="Edit <b>Profile</b>">
        <form @submit.prevent="updateProfile">
          <input-image
            v-model="form.image"
            :image-url="authUser.image"
            width="150"
            height="150"
            :label="' Image (max:1000X1000px)'"
            :error-text="errors.first('image')"
          ></input-image>
          <div class="text-center">
            <small
              class="text-center text-danger"
              v-if="form.errors.has('image')"
              >{{ form.errors.get("image") }}
            </small>
          </div>

          <input-text
            label="Name"
            name="name"
            v-model="form.name"
            v-validate="'required'"
            :error-text="errors.first('name')"
            required
          ></input-text>
          <input-text
            label="Email"
            type="email"
            name="email"
            v-model="form.email"
            v-validate="'required|email'"
            :error-text="errors.first('email')"
            required
          ></input-text>

          <div class="text-right">
            <button :disabled="errors.any()" class="btn btn-success">
              Update
            </button>
          </div>
        </form>
      </app-card>
    </div>
  </div>
</template>

<script>
import Common from "@layouts/Common";
import { UPDATE_PROFILE_URL } from "@routes/admin";
import Form from "@utils/Form";

export default {
  name: "UserProfile",

  extends: Common,

  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        image: "",
        password: "",
        passwordConfirmation: "",
      }),
    };
  },

  methods: {
    updateProfile() {
      this.$validator.validate().then((result) => {
        if (result) {
          this.form
            .post(UPDATE_PROFILE_URL)
            .then((data) => {
              this.password = this.passwordConfirmation = "";
              this.$store.commit("setAuthUser", data.data);
              alertMessage("Your profile is successfully changed.");
            })
            .catch((error) => {
              switch (error.status) {
                case 422:
                  this.form.errors.initialize(error.data.errors);
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

  created() {
    this.form.name = this.authUser.name;
    this.form.email = this.authUser.email;
  },
};
</script>
