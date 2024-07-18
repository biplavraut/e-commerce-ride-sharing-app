import Model from "./Model";
import { DISCOUNT_INDEX_URL } from "@routes/admin";

export default class Discount extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = DISCOUNT_INDEX_URL;
    this.namePlural = "discounts";
    this.nameLowerCase = "discount";
  }
}
