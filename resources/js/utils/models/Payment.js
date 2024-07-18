import Model from "./Model";
import { PAYMENT_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Donation extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PAYMENT_INDEX_URL;
    this.namePlural = "donations";
    this.nameLowerCase = "donation";
  }

  reset() {
    return Api.get(this.indexUrl);
  }
}
