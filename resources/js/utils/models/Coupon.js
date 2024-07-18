import Model from "./Model";
import { COUPON_INDEX_URL } from "@routes/admin";

export default class Coupon extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = COUPON_INDEX_URL;
    this.namePlural = "coupons";
    this.nameLowerCase = "coupon";
  }
}
