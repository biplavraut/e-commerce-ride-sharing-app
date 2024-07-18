import Model from "./Model";
import { VENDOR_DISCOUNT_INDEX_URL } from "@routes/admin";

export default class VendorDiscount extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = VENDOR_DISCOUNT_INDEX_URL;
    this.namePlural = "vendor-discounts";
    this.nameLowerCase = "vendor-discount";
  }
}
