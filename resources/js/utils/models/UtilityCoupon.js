import Model from "./Model";
import { UTILITY_COUPON_INDEX_URL } from "@routes/admin";

export default class UtilityCoupon extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = UTILITY_COUPON_INDEX_URL;
        this.namePlural = "utility-coupons";
        this.nameLowerCase = "utility-coupon";
    }
}