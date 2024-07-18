<template>
  <div class="row">
    <div class="col-md-7">
      <app-card :title="edit ? 'Edit ' + form.code : 'Add New <b>Voucher</b>'">
        <form @submit.prevent="saveData">
          <div class="row">
            <div class="col-md-6">
              <input-text
                label="Code (Auto Generate After User Selection)"
                name="code"
                v-validate="'required'"
                v-model="form.code"
                required
                :error-text="errors.first('code')"
                disabled
              ></input-text>
            </div>

            <div class="col-md-6">
              <input-text
                label="Amount"
                name="amount"
                v-validate="'required'"
                v-model="form.amount"
                required
                :error-text="errors.first('amount')"
              ></input-text>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <p>Voucher for: {{ userName }}</p>
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
    </div>
    <div class="col-md-5">
      <app-card title="User List">
        <input-text
          label="Search User (Name, Phone, Email)"
          name="search-text"
          v-model="searchKeyword"
          @input="searchUser"
        ></input-text>
        <ul class="list-group list-group-flush" v-if="searchKeyword.length > 0">
          <li class="list-group-item" v-for="user in users" :key="user.id">
            <div class="row">
              <div class="col-md-9">
                {{ user.name }}
              </div>
              <div class="col-md-3">
                <span
                  ><button
                    @click="addUser(user.id, user.name)"
                    class="btn btn-sm btn-primary"
                  >
                    Add
                  </button></span
                >
              </div>
            </div>
          </li>
        </ul>
      </app-card>
    </div>
  </div>
</template>


<script>
import Form from "@utils/Form";
import Voucher from "@utils/models/Voucher";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "VoucherCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        code: "",
        amount: 0,
        userId: 0,
      }),
      searchKeyword: "",
      userName: "",
      users: [],
      model: new Voucher(),
    };
  },

  methods: {
    async storeData() {
      if (this.form.amount < 1) {
        alertMessage("Please input valid amount to continue.", "danger");
        return;
      }

      if (confirm("Are you sure? You cannot undo this action.")) {
        this.form.errors.clear();

        try {
          await this.model.store(this.form.data());
          alertMessage("Data saved successfully.");
          this.model.cache.invalidate();

          this.$router.push({
            name: `voucher.index`,
          });
        } catch (error) {
          this.form.errors.initialize(error.data.errors);
        }
      }
    },
    async updateData() {
      if (this.form.amount < 1) {
        alertMessage("Please input valid amount to continue.", "danger");
        return;
      }

      if (confirm("Are you sure?")) {
        try {
          let data = await this.model.update(
            this.$route.params.id,
            this.form.data(true)
          );
          this.model.cache.invalidate();
          alertMessage("Data updated successfully.");
          this.$router.push({
            name: `voucher.index`,
          });
        } catch (error) {
          alertMessage("The given data was invalid.", "danger");
          this.form.errors.initialize(error.data.errors);
        }
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { code, amount, user } = data.data;

      this.form = new Form({
        code,
        amount,
        userId: user.id,
      });
      this.userName =  user.first_name + " " + user.last_name + "(" + user.phone + ")";
    },
    searchUser: debounce(function (e) {
      axios
        .get(this.model.indexUrl + "/user-list?name=" + this.searchKeyword)
        .then((response) => {
          let users = response.data;
          this.users = users.map((item) => {
            return {
              id: item.id,
              name:
                item.first_name + " " + item.last_name + "(" + item.phone + ")",
              phone: item.phone,
            };
          });
        });
    }, 2000),
    addUser(userId, userName) {
      this.form.userId = userId;
      this.userName = userName;
      this.searchKeyword = "";
      const code = "GOGO#$" + randomString(8).toUpperCase();
      this.form.code = code.substring(0, 10);
      alertMessage("User has been selected.");
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },

  watch: {},
};
</script>
