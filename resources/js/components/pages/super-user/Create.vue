<template>
<div>
  <app-card
    :title="edit ? 'Edit ' + form.name : 'Add New <b>Operational User</b>'"
  >
    <form @submit.prevent="saveData" @change="clearError">
      <div class="row">
        <div class="col-md-4">
          <input-text
            label="Name"
            name="name"
            v-model="form.name"
            v-validate="'required'"
            required
            :error-text="errors.first('name')"
          ></input-text>
          <small class="text-center text-danger" v-if="form.errors.has('name')"
            >* {{ form.errors.get("name") }}</small
          >
        </div>
        <div class="col-md-4">
          <input-text
            label="E-mail"
            name="email"
            v-validate="'email|required'"
            v-model="form.email"
            :error-text="errors.first('email')"
            required
          ></input-text>
          <small class="text-center text-danger" v-if="form.errors.has('email')"
            >* {{ form.errors.get("email") }}</small
          >
        </div>
        <div class="col-md-4">
          <input-text
            label="Phone"
            name="phone"
            type="number"
            v-model="form.phone"
            v-validate="'required'"
            required
            :error-text="errors.first('phone')"
          ></input-text>
          <small class="text-center text-danger" v-if="form.errors.has('phone')"
            >* {{ form.errors.get("phone") }}</small
          >
        </div>

        <div class="col-md-12">
          <div class="form-group asdh-select" name="role">
            <label>Type of Role *</label>
            <select
              class="form-control"
              v-model="form.type"
              :error-text="errors.first('type')"
              v-validate="'required'"
              required
            >
              <option value disabled>Select Role</option>
              <option
                data-tokens=""
                :value="option.id"
                v-for="(option, index) in types"
                :key="index"
              >
                {{ option.name.toUpperCase() }}
              </option>
            </select>
          </div>
          <small class="text-center text-danger" v-if="form.errors.has('type')"
            >* {{ form.errors.get("type") }}</small
          >
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <button
            type="submit"
            :disabled="form.errors.any(errors.any())"
            class="btn btn-success pull-right"
          >
            {{ edit ? "Update" : "Save" }}
          </button>
        </div>
      </div>
    </form>
  </app-card>
  <!-- Update Password -->
  <app-card v-if="edit && this.authUser.email == 'drb@gogo20.com'"
    title="Change Password"
  >
    <form @submit.prevent="updatePassword">
      <div class="row">
        <div class="col-md-4">
          <input-text
            type="password"
            label="Password"
            name="password"
            @keyup="checkPassword" 
            v-model="upForm.password"
            :error-text="errors.first('password')"
          ></input-text>
          <small class="text-center text-danger" v-if="upForm.errors.has('password')"
            >* {{ upForm.errors.get("password") }}</small
          >
        </div>
        <div class="col-md-4">
          <input-text
            type="password"
            label="Confim Password"
            name="confirmpassword"
            @keyup="checkPassword" 
            v-model="upForm.confirmpassword"
          ></input-text>
          <small class="text-center text-danger" v-if="form.errors.has('confirmpassword')"
            >* {{ upForm.errors.get("confirmpassword") }}</small
          >
        </div>
        <span v-if="showPassError">Password does not match.</span>
      </div>

      <div class="row">
        <div class="col-md-12">
          <button
            type="submit"
            :disabled="showPassError"
            class="btn btn-success pull-right"
          >
            Update Password
          </button>
        </div>
      </div>
    </form>
  </app-card>
  </div>
</template>

<script>
import Form from "@utils/Form";
import SuperUser from "@utils/models/SuperUser";
import { store, save } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "SuperUserCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        name: "",
        email: "",
        phone: "",
        type: "",
        verified: 1,
        password: "gogo20changeyourpassword",
      }),
      upForm: new Form({
        password: "",
        confirmpassword: "",
      }),
      model: new SuperUser(),
      types: [
        {
          id: "admin",
          name: "Admin",
        },
        {
          id: "unit-head",
          name: "Unit Head (HOD)",
        },
        {
          id: "manager",
          name: "Manager",
        },
        {
          id: "officer",
          name: "Officer",
        },
        {
          id: "support",
          name: "Support",
        },
      ],
      showPassError: false
    };
  },

  methods: {
    async storeData() {
      if (this.authUser.email != "drb@gogo20.com") {
        return swal(
          "Oh noes!",
          "You don't have access on this. Please contact to adminstrator.",
          "error"
        );
      }

      this.form.errors.clear();

      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();

        this.$router.push({
          name: `super-user.index`,
        });
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async updateData() {
      if (this.authUser.email != "drb@gogo20.com") {
        return swal(
          "Oh noes!",
          "You don't have access on this. Please contact to adminstrator.",
          "error"
        );
      }
      try {
        let data = await this.model.update(
          this.$route.params.id,
          this.form.data(true)
        );
        this.model.cache.invalidate();
        alertMessage("Data updated successfully.");

        this.$router.push({
          name: `super-user.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async updatePassword() {
      if (this.authUser.email != "drb@gogo20.com") {
        return swal(
          "Oh noes!",
          "You don't have access on this. Please contact to adminstrator.",
          "error"
        );
      }
      if(this.upForm.password.length < 8){
        return swal(
          "Oh noes!",
          "Password must be of minimum 8 characters.",
          "error"
        );
      }
      try {
        let data = await this.upForm.post("/admin/super-user/update-password/" + this.$route.params.id);
        alertMessage("Password updated successfully.");
        this.$router.push({
          name: `super-user.index`,
        });
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.upForm.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { name, email, phone, type } = data.data;

      this.form = new Form({
        name,
        email,
        phone,
        type,
      });

      this.types = [
        {
          id: "admin",
          name: "Admin",
        },
        {
          id: "unit-head",
          name: "Unit Head (HOD)",
        },
        {
          id: "manager",
          name: "Manager",
        },
        {
          id: "officer",
          name: "Officer",
        },
        {
          id: "support",
          name: "Support",
        },
      ];
    },
    clearError() {
      this.form.errors.errors = {};
    },
    checkPassword(){
      if(this.upForm.password != '' && this.upForm.confirmpassword != ''){
        if(this.upForm.password == this.upForm.confirmpassword){
          this.showPassError = false
        }else{
          this.showPassError = true
        }
      }
      
    }
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },
  computed: {
    ...mapGetters(["authUser"]),
  },

  watch: {},
};
</script>
