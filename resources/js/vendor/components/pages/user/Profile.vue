<template>
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <app-card title="Edit <b>Profile</b>">
        <form @submit.prevent="updateProfile">
          <input-image
            v-model="form.image"
            :image-url="authUser.image"
            :label="' Image (max:1000X1000px)'"
            width="150"
            height="150"
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
            label="Business Name"
            name="business_name"
            v-model="form.business_name"
            v-validate="'required'"
            :error-text="errors.first('business_name')"
            required
          ></input-text>
          <input-text
            label="First Name"
            name="first_name"
            v-model="form.first_name"
            v-validate="'required'"
            :error-text="errors.first('first_name')"
            required
          ></input-text>
          <input-text
            label="Last Name"
            name="last_name"
            v-model="form.last_name"
            v-validate="'required'"
            :error-text="errors.first('last_name')"
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

          <input-text
            label="Phone"
            type="text"
            name="phone"
            v-model="form.phone"
            v-validate="'required'"
            :error-text="errors.first('phone')"
            required
          ></input-text>

          <input-text
            label="Address"
            type="text"
            name="address"
            v-model="form.address"
            v-validate="'required'"
            :error-text="errors.first('address')"
            required
          ></input-text>

          <input-text
            label="Latitude"
            type="text"
            name="lat"
            v-model="form.lat"
            v-validate="'required'"
            :error-text="errors.first('lat')"
            required
          ></input-text>

          <input-text
            label="Longitude"
            type="text"
            name="long"
            v-model="form.long"
            v-validate="'required'"
            :error-text="errors.first('long')"
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
        business_name: "",
        email: "",
        first_name: "",
        last_name: "",
        image: "",
        password: "",
        passwordConfirmation: "",
        email: "",
        address: "",
        lat: "",
        long: "",
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
    this.form.business_name = this.authUser.name;
    this.form.first_name = this.authUser.firstName;
    this.form.last_name = this.authUser.lastName;
    this.form.email = this.authUser.email;
    this.form.phone = this.authUser.phone;
    this.form.address = this.authUser.address;
    this.form.lat = this.authUser.lat;
    this.form.long = this.authUser.long;
  },
};
</script>
