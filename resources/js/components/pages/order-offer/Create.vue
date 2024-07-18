<template>
  <app-card :title="'Order Offer Discount Configuration'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="col-md-12">
          <input-text
            label="Offer Title"
            type="text"
            name="offer_title"
            v-model="form.orderTitle"
            v-validate="'required'"
            :error-text="errors.first('offer_title')"
            required
          ></input-text>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <input-text
            label="No. of Orders"
            type="number"
            name="no_of_orders"
            v-model="form.noOfOrders"
            v-validate="'required'"
            :error-text="errors.first('no_of_orders')"
            required
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Discount(%)"
            type="number"
            name="discount"
            v-model="form.discount"
            v-validate="'required'"
            :error-text="errors.first('dicount')"
            required
          ></input-text>
        </div>

        <div class="row" style="margin:5px">
          <div class="col-md-6">
            <input-text
              label="From Date"
              v-model="from_date"
              :error-text="errors.first('from')"
              datepicker
              required
              @input="checkFromDate()"
            ></input-text>
            <small
              class="text-center text-danger"
              v-if="form.errors.has('from')"
              >* {{ form.errors.get("from") }}</small
            >
          </div>
          <div class="col-md-6">
            <input-text
              label="From Time"
              v-model="from_time"
              :error-text="errors.first('from')"
              timepicker
              required
              @input="checkFromDate()"
            ></input-text>
            <small
              class="text-center text-danger"
              v-if="form.errors.has('from')"
              >* {{ form.errors.get("from") }}</small
            >
          </div>
        </div>
        <div class="row" style="margin:5px">
          <div class="col-md-6">
            <input-text
              label="To Date"
              v-model="to_date"
              :error-text="errors.first('to')"
              datepicker
              required
              @input="checkToDate()"
            ></input-text>
          </div>
          <div class="col-md-6">
            <input-text
              label="To Time"
              v-model="to_time"
              :error-text="errors.first('to')"
              timepicker
              required
              @input="checkToDate()"
            ></input-text>
          </div>
          <small class="text-center text-danger" v-if="form.errors.has('to')"
            >* {{ form.errors.get("to") }}</small
          >
        </div>
        <div class="row" style="margin:5px">
          <div class="col-md-6">
            <input-checkbox
              label="Enabled"
              v-model="form.enabled"
            ></input-checkbox>
          </div> 
          <div class="col-md-6">
            <button type="submit" class="btn btn-success pull-right">
              {{ edit ? "Update" : "Save" }}
            </button>
            <br />
          </div>          
        </div>

        
      </div>
    </form>
  </app-card>
</template>

<script>
import Form from "@utils/Form";
import OrderOfferConf from "@utils/models/OrderOfferConf";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "OrderOfferConfCreate",
  mixins: [store, save],
  data() {
    return {
      edit: false,
      form: new Form({
        orderTitle:"",
        noOfOrders:'',
        discount:'',
        from:'',
        to:'',
        enabled:''
      }),
      from_date:'',
      from_time:'',
      to_date:'',
      to_time:'',
      model: new OrderOfferConf(),
    };
  },

  methods: {
    async storeData() {
      this.form.errors.clear();
      try {
        await this.model.store(this.form.data());
        alertMessage("Data saved successfully.");
        this.model.cache.invalidate();
      } catch (error) {
        this.form.errors.initialize(error.data.errors);
      }
    },
    async getData() {
      axios.get(this.model.indexUrl).then((response) => {
        if(response.data.data){
          let {
            orderTitle,noOfOrders,discount,enabled,from,to,
            from_date,
            from_time,
            to_date,
            to_time,     
          } = response.data.data;
          this.form = new Form({
            orderTitle,noOfOrders,discount,from,to,enabled
          });
          this.from_date = from_date;
          this.from_time = from_time;
          this.to_date = to_date;
          this.to_time = to_time;
        }
        
      });
    },
    checkFromDate() {
      this.form.from = this.from_date + " " + this.from_time;
      this.clearError();
    },
    checkToDate() {
      this.form.to = this.to_date + " " + this.to_time;
      this.clearError();
    },
    clearError() {
      this.form.errors.errors = {};
    },
  },
  mounted() {
    this.getData();
  },
  watch: {},
};
</script>

<style scoped>
.my-sub-heading {
  text-transform: unset;
}
</style>
