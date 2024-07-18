<template>
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <app-card title="<b>Change Password</b>">
        <form @submit.prevent="changePassword">
          <input-text label="Old Password"
                      name="oldPassword"
                      type="password"
                      v-model="form.oldPassword"
                      :error-text="errors.first('oldPassword')"
                      v-validate="'required|min:6'"></input-text>
          <input-text label="New Password"
                      name="newPassword"
                      type="password"
                      ref="newPassword"
                      v-model="form.newPassword"
                      :error-text="errors.first('newPassword')"
                      v-validate="'required|min:6'"></input-text>
          <input-text label="Confirm New Password"
                      name="newPasswordConfirmation"
                      type="password"
                      v-model="form.newPasswordConfirmation"
                      :error-text="errors.first('newPasswordConfirmation')"
                      v-validate="'required|min:6|confirmed:newPassword'"></input-text>

          <div class="text-right">
            <button type="submit"
                    :disabled="errors.any()"
                    class="btn btn-success btn-fill">Change
            </button>
          </div>
        </form>
      </app-card>
    </div>
  </div>
</template>

<script>
  import Common from "@layouts/Common";
  import {CHANGE_PASSWORD_URL} from "@routes/admin";
  import Form from "@utils/Form";

  export default {
    name: "ChangePassword",

    extends: Common,

    data() {
      return {
        form: new Form({
          oldPassword: "",
          newPassword: "",
          newPasswordConfirmation: ""
        })
      };
    },

    methods: {
      changePassword() {
        this.$validator.validate().then(result => {
          if (result) {
            this.form
                .post(CHANGE_PASSWORD_URL)
                .then(data => {
                  if (data.status) {
                    alertMessage(data.message);
                    this.form.reset();
                    this.$validator.reset();
                  } else {
                    alertMessage(data.message, 'danger');
                  }
                })
                .catch(error => {
                  switch (error.status) {
                    case 422:
                      this.form.errors.initialize(error.data.errors);
                      break;
                    default:
                      alertMessage(error.data.message, 'danger');
                      break;
                  }
                });
          } else {
            Helpers.focusFirstError(this.errors);
          }
        });
      }
    }
  };
</script>
