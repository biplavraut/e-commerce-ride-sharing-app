<template>
  <div class="row">
    <div class="col-md-7">
      <app-card :title="edit ? 'Edit ' + form.name : 'Add New <b>Campaign</b>'">
        <form @submit.prevent="saveData">
          <div class="row">
            <div class="col-md-4">
              <input-text
                label="Name"
                name="name"
                v-validate="'required'"
                v-model="form.name"
                required
                :error-text="errors.first('name')"
              ></input-text>
            </div>
            <div class="col-md-4">
              <input-text
                label="Held On"
                name="held_on"
                type="date"
                v-model="form.heldOn"
                :error-text="errors.first('held_on')"
              ></input-text>
            </div>
            <div class="col-md-4">
              <div class="form-group asdh-select" name="role">
                <label>User Type</label>
                <select
                  class="form-control"
                  v-model="form.userType"
                  :disabled="form.winners.length > 0"
                >
                  <option value disabled>Select User Type</option>
                  <option
                    data-tokens=""
                    :value="option.id"
                    v-for="(option, index) in userTypes"
                    :key="index"
                  >
                    {{ option.name.toUpperCase() }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="row" v-if="form.winners.length > 0">
            <app-card title="Winner List">
              <ul>
                <li
                  class="badge"
                  v-for="(item, index) in form.winners"
                  :key="index"
                >
                  {{
                    form.userType === "user"
                      ? "App User"
                      : form.userType.toUpperCase()
                  }}
                  Phone: {{ item }} | Prize: {{ form.prizes[index] }} | Prize
                  Type: {{ form.types[index] }}
                </li>
              </ul>
              <span
                class="btn btn-sm btn-round btn-danger"
                @click="clearWinnerList"
                >clear list</span
              >
            </app-card>
          </div>

          <div class="row">
            <div class="col-md-12">
              <app-quill-editor
                label="Description"
                name="description"
                v-model="form.description"
                :error-text="errors.first('description')"
              ></app-quill-editor>

              <button
                type="submit"
                :disabled="form.errors.any(errors.any())"
                class="btn btn-success pull-right"
              >
                {{ edit ? "Update" : "Send" }}
              </button>
            </div>
          </div>
        </form>
      </app-card>
    </div>
    <div class="col-md-5">
      <app-card title="Winner &amp; Prizes">
        <input-text
          :label="searchLabel"
          name="search-text"
          v-model="searchKeyword"
          @input="searchUser"
        ></input-text>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="user in users" :key="user.id">
            <div class="row">
              <div class="col-md-9">
                <span v-if="form.userType === 'vendor'">{{
                  user.business_name
                }}</span>
                <span v-else>{{ user.first_name }} {{ user.last_name }}</span>
                <small>{{ user.phone }}</small>
              </div>
              <div class="col-md-3">
                <span
                  ><button
                    @click="addUser(user.phone)"
                    class="btn btn-sm btn-primary"
                  >
                    Select
                  </button></span
                >
              </div>
            </div>
          </li>
        </ul>
      </app-card>
    </div>

    <div class="modal fade" id="prize" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Prizes</h4>
          </div>
          <div class="modal-body">
            <div class="form-group asdh-select" name="role">
              <label>Prize Type</label>
              <select class="form-control" v-model="prizeModal.type">
                <option value disabled>Select Prize Type</option>
                <option
                  data-tokens=""
                  :value="option.id"
                  v-for="(option, index) in prizeTypes"
                  :key="index"
                >
                  {{ option.name.toUpperCase() }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>
                Enter Prize
                {{ prizeModal.type === "amount" ? "Amount" : "Belonging" }}
              </label>
              <input
                :type="prizeModal.type === 'amount' ? 'number' : 'text'"
                class="form-control"
                v-model="prizeModal.prize"
              />
            </div>

            <button
              type="submit"
              class="btn btn-round btn-primary"
              @click="setPrize()"
            >
              Set
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import Form from "@utils/Form";
import Campaign from "@utils/models/Campaign";
import { store, save } from "@utils/mixins/Crud";
import { mapGetters } from "vuex";

export default {
  name: "CampaignCreate",

  mixins: [store, save],

  data() {
    return {
      edit: false,
      form: new Form({
        name: "",
        heldOn: "",
        description: "",
        winners: [],
        prizes: [],
        types: [],
        userType: "user",
      }),
      searchKeyword: "",
      users: [],
      model: new Campaign(),
      prizeModal: {
        prize: "",
        type: "",
        phone: "",
      },
      prizeTypes: [
        {
          id: "amount",
          name: "amount",
        },
        {
          id: "belonging",
          name: "belonging",
        },
      ],
      userTypes: [
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
    async storeData() {
      if (this.authUser.email != "drb@gogo20.com") {
        return swal(
          "Oh noes!",
          "You don't have access on this. Please contact to adminstrator.",
          "error"
        );
      }

      if (confirm("Are you sure? You cannot undo this action.")) {
        this.form.errors.clear();

        try {
          await this.model.store(this.form.data());
          alertMessage("Data saved successfully.");
          this.model.cache.invalidate();

          this.$router.push({
            name: `campaign.index`,
          });
        } catch (error) {
          this.form.errors.initialize(error.data.errors);
        }
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
      } catch (error) {
        alertMessage("The given data was invalid.", "danger");
        this.form.errors.initialize(error.data.errors);
      }
    },

    async getData() {
      let data = await this.model.show(this.$route.params.id);
      let { code, amount, validTill } = data.data;

      this.form = new Form({
        code,
        amount,
        validTill,
      });
    },
    searchUser() {
      axios
        .get(
          this.model.indexUrl +
            "/user-list?name=" +
            this.searchKeyword +
            "&type=" +
            this.form.userType
        )
        .then((response) => {
          this.users = response.data;
        });
    },
    addUser(phone) {
      const found = this.form.winners.find((element) => element === phone);
      if (found === phone) {
        alertMessage("Prize already allocated to this user.", "danger");
        return;
      } else {
        this.prizeModal.phone = phone;
        $("#prize").modal("show");
      }
    },
    setPrize() {
      if (
        confirm("Are you sure? You cant undo this process.") &&
        this.prizeModal.prize &&
        this.prizeModal.type
      ) {
        this.form.winners.push(this.prizeModal.phone);
        this.form.prizes.push(this.prizeModal.prize);
        this.form.types.push(this.prizeModal.type);
        this.searchKeyword = "";
        this.users = [];
        this.prizeModal = {
          prize: "",
          type: "",
          phone: "",
        };
        $("#prize").modal("hide");
      } else if (!this.prizeModal.prize || !this.prizeModal.type) {
        alertMessage("Please set prize and prize type to continue.", "danger");
      }
    },
    clearWinnerList() {
      if (confirm("Are you sure? You cant undo this process.")) {
        this.form.winners = [];
      }
    },
  },

  mounted() {
    this.edit = this.$route.params.hasOwnProperty("id");

    if (this.edit) {
      this.getData();
    }
  },
  computed: {
    ...mapGetters(["authUser"]),

    searchLabel: function () {
      if (this.form.userType == "user") {
        return "Search App User (Name, Email, Phone)...";
      } else if (this.form.userType == "rider") {
        return "Search Rider (Name, Email, Phone)...";
      } else {
        return "Search Vendor (Name, Email, Phone)...";
      }
    },
  },

  watch: {},
};
</script>
