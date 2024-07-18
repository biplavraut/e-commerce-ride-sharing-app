<template>
  <app-card :title="'Global Configuration (Price & Time)'">
    <form @submit.prevent="saveData">
      <div class="row">
        <div class="row">
          <div class="col-md-10">
            <h3 class="my-sub-heading">gogoCab</h3>
          </div>
        </div>
        <div class="col-md-6">
          <input-text
            label="Rider Request Radius (RRR in KM)"
            type="number"
            name="rider_nearby_radius"
            v-model="form.riderNearbyRadius"
            v-validate="'required'"
            :error-text="errors.first('rider_nearby_radius')"
            required
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Rider Credit (in Rs.)"
            type="number"
            name="rider_credit"
            v-model="form.riderCredit"
            v-validate="'required'"
            :error-text="errors.first('rider_credit')"
            required
          ></input-text>
        </div>

        <br />
        <div class="row">
          <div class="col-md-10">
            <h3 class="my-sub-heading">gogoPool</h3>
          </div>
        </div>
        <div class="col-md-6">
          <input-text
            label="2 Wheeler Pool Pice Per Seat Per KM (in Rs)"
            type="number"
            name="pool_bike_per_km_per_seat"
            v-model="form.poolBikePerKmPerSeat"
            v-validate="'required'"
            :error-text="errors.first('pool_bike_per_km_per_seat')"
            required
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="4 Wheeler Pool Pice Per Seat Per KM (in Rs)"
            type="number"
            name="poolCarPerKmPerSeat"
            v-model="form.poolCarPerKmPerSeat"
            v-validate="'required'"
            :error-text="errors.first('pool_car_per_km_per_seat')"
            required
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">On-Demand</h3>
          </div>
        </div>

        <div class="col-md-4">
          <input-text
            label="Free Delivery After (in Rs.)"
            type="number"
            name="free_delivery_after"
            v-model="form.freeDeliveryAfter"
            v-validate="'required'"
            :error-text="errors.first('free_delivery_after')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Delivery Charge Inside RingRoad (in Rs.)"
            type="number"
            name="delivery_charge"
            v-model="form.deliveryCharge"
            v-validate="'required'"
            :error-text="errors.first('delivery_charge')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Delivery Charge Outside RingRoad (in Rs.)"
            type="number"
            name="delivery_charge_outside"
            v-model="form.deliveryChargeOutside"
            v-validate="'required'"
            :error-text="errors.first('delivery_charge_outside')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Min Order Limit (in Rs.)"
            type="number"
            name="min_order_limit"
            v-model="form.minOrderLimit"
            v-validate="'required'"
            :error-text="errors.first('min_order_limit')"
            required
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">gogoCab Night Surge Time</h3>
          </div>
        </div>

        <div class="col-md-6">
          <input-text
            label="Night Surge Start Time"
            type="time"
            name="night_surge_start"
            v-model="form.nightSurgeStart"
            v-validate="'required'"
            :error-text="errors.first('night_surge_start')"
            required
          ></input-text>
        </div>
        <div class="col-md-6">
          <input-text
            label="Night Surge End Time"
            type="time"
            name="night_surge_end"
            v-model="form.nightSurgeEnd"
            v-validate="'required'"
            :error-text="errors.first('night_surge_end')"
            required
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">
              First Download &amp; Refer (App User)
            </h3>
          </div>
        </div>

        <div class="col-md-4">
          <input-text
            label="First Download (in Rs.) "
            type="number"
            name="first_download_reward"
            v-model="form.firstDownloadReward"
            v-validate="'required'"
            :error-text="errors.first('first_download_reward')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Referral User Reward Point (in Rs.)"
            type="number"
            name="referral_user_point"
            v-model="form.referralUserPoint"
            v-validate="'required'"
            :error-text="errors.first('referral_user_point')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Referred User Reward Point (in Rs.)"
            type="number"
            name="referred_user_point"
            v-model="form.referredUserPoint"
            v-validate="'required'"
            :error-text="errors.first('referred_user_point')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="User Refer Limit "
            type="number"
            name="user_refer_limit"
            v-model="form.userReferLimit"
            v-validate="'required'"
            :error-text="errors.first('user_refer_limit')"
            required
          ></input-text>
        </div>
        <div class="col-md-12">
            <input-text
            label="User Refer Text "
            name="user_refer_text"
            v-model="form.userReferText"
            v-validate="'required'"
            :error-text="errors.first('user_refer_text')"
            required
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">First Download &amp; Refer (Rider)</h3>
          </div>
        </div>

        <div class="col-md-4">
          <input-text
            label="First Download (in Rs.) "
            type="number"
            name="rider_first_download_reward"
            v-model="form.riderFirstDownloadReward"
            v-validate="'required'"
            :error-text="errors.first('rider_first_download_reward')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Referral User Reward Point (in Rs.)"
            type="number"
            name="rider_referral_user_point"
            v-model="form.riderReferralUserPoint"
            v-validate="'required'"
            :error-text="errors.first('rider_referral_user_point')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Referred User Reward Point (in Rs.)"
            type="number"
            name="rider_referred_user_point"
            v-model="form.riderReferredUserPoint"
            v-validate="'required'"
            :error-text="errors.first('rider_referred_user_point')"
            required
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Rider Refer Limit "
            type="number"
            name="rider_refer_limit"
            v-model="form.riderReferLimit"
            v-validate="'required'"
            :error-text="errors.first('rider_refer_limit')"
            required
          ></input-text>
        </div>
        <br />
        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">Reward Redeem Limit</h3>
          </div>
        </div>
        <div class="col-md-4">
          <input-text
            label="Reward Redeem Limit (in %)"
            type="number"
            name="reward_redeem_limit"
            v-model="form.rewardRedeemLimit"
            v-validate="'required'"
            :error-text="errors.first('reward_redeem_limit')"
            required
          ></input-text>
        </div>
        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">Cashback</h3>
          </div>
        </div>

        <div class="col-md-4">
          <input-text
            label="Cashback (in %)"
            name="cashback_percent"
            v-model="form.cashbackPercent"
            v-validate="form.purchaseTotal ? 'required' : ''"
            :error-text="errors.first('cashback_percent')"
            :required="form.purchaseTotal ? 'required' : ''"
          ></input-text>
        </div>
        <div class="col-md-4">
          <input-text
            label="Purchase of Total (in Nrs.)"
            type="number"
            name="purchase_total"
            v-model="form.purchaseTotal"
            v-validate="form.cashbackPercent ? 'required' : ''"
            :error-text="errors.first('purchase_total')"
            :required="form.cashbackPercent ? 'required' : ''"
          ></input-text>
        </div>

        <div class="col-md-4">
          <input-text
            label="Dine-In Cashback (in %)"
            type="text"
            name="dinin_cashback"
            v-model="form.dineinCashback"
            :error-text="errors.first('dinin_cashback')"
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">Utility</h3>
          </div>
        </div>

        <div class="col-md-12">
          <input-text
            label="Utility Service Promo Text"
            name="utility_promo"
            v-model="form.utilityPromo"
            :error-text="errors.first('utility_promo')"
          ></input-text>
        </div>

        <br />

        <div class="row" style="margin-top: 5%">
          <div class="col-md-10">
            <h3 class="my-sub-heading">App Update</h3>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <input-text
              label="User App Latest Version"
              name="user_app_version"
              v-model="form.userAppVersion"
              :error-text="errors.first('user_app_version')"
            ></input-text>
          </div>
          <div class="col-md-4">
            <input-checkbox
              label="User App Minor Update"
              v-model="form.userAppMinor"
            ></input-checkbox>
          </div>
          <div class="col-md-4">
            <input-checkbox
              label="User App Forcefully Update"
              v-model="form.userAppMajor"
            ></input-checkbox>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <input-text
              label="Partner App Latest Version"
              name="partner_app_version"
              v-model="form.partnerAppVersion"
              :error-text="errors.first('partner_app_version')"
            ></input-text>
          </div>
          <div class="col-md-4">
            <input-checkbox
              label="Partner App Minor Update"
              v-model="form.partnerAppMinor"
            ></input-checkbox>
          </div>
          <div class="col-md-4">
            <input-checkbox
              label="Partner App Forcefully Update"
              v-model="form.partnerAppMajor"
            ></input-checkbox>
          </div>
        </div>

        <div class="col-md-12">
          <button type="submit" class="btn btn-success pull-right">
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
import DefaultConf from "@utils/models/DefaultConf";
import { store, save } from "@utils/mixins/Crud";

export default {
  name: "ConfCreate",
  mixins: [store, save],
  data() {
    return {
      edit: false,
      form: new Form({
        riderNearbyRadius: 5,
        riderCredit: 1000,
        freeDeliveryAfter: 1000,
        deliveryCharge: 100,
        deliveryChargeOutside: 100,
        nightSurgeStart: "22:00:00",
        nightSurgeEnd: "04:00:00",
        firstDownloadReward: 0,
        referralUserPoint: 25,
        referredUserPoint: 50,
        cashbackPercent: "",
        purchaseTotal: "",
        userAppVersion: "",
        userAppMinor: false,
        userAppMajor: false,
        partnerAppVersion: "",
        partnerAppMinor: false,
        partnerAppMajor: false,
        riderFirstDownloadReward: 0,
        riderReferralUserPoint: 25,
        riderReferredUserPoint: 0,
        utilityPromo: "",
        dineinCashback: 0,
        riderReferLimit:20,
        userReferLimit:250,
        rewardRedeemLimit:15,
        minOrderLimit:500,
        userReferText:"",
        poolBikePerKmPerSeat: 10,
        poolCarPerKmPerSeat: 5
      }),
      model: new DefaultConf(),
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
        let {
          riderNearbyRadius,
          riderCredit,
          freeDeliveryAfter,
          deliveryCharge,
          deliveryChargeOutside,
          nightSurgeStart,
          nightSurgeEnd,
          firstDownloadReward,
          referralUserPoint,
          referredUserPoint,
          cashbackPercent,
          purchaseTotal,
          userAppVersion,
          userAppMinor,
          userAppMajor,
          partnerAppVersion,
          partnerAppMinor,
          partnerAppMajor,
          riderFirstDownloadReward,
          riderReferralUserPoint,
          riderReferredUserPoint,
          utilityPromo,
          dineinCashback,
          riderReferLimit,
          userReferLimit,
          rewardRedeemLimit,
          minOrderLimit,
          userReferText,
          poolBikePerKmPerSeat,
          poolCarPerKmPerSeat
        } = response.data.data;
        this.form = new Form({
          riderNearbyRadius,
          riderCredit,
          freeDeliveryAfter,
          deliveryChargeOutside,
          deliveryCharge,
          nightSurgeStart,
          nightSurgeEnd,
          firstDownloadReward,
          referralUserPoint,
          referredUserPoint,
          cashbackPercent,
          purchaseTotal,
          userAppVersion,
          userAppMinor,
          userAppMajor,
          partnerAppVersion,
          partnerAppMinor,
          partnerAppMajor,
          riderFirstDownloadReward,
          riderReferralUserPoint,
          riderReferredUserPoint,
          utilityPromo,
          dineinCashback,
          riderReferLimit,
          userReferLimit,
          rewardRedeemLimit,
          minOrderLimit,
          userReferText,
          poolBikePerKmPerSeat,
          poolCarPerKmPerSeat,
        });
      });
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
