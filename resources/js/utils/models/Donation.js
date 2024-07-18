import Model from "./Model";
import { DONATION_INDEX_URL } from "@routes/admin";

export default class Payment extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = DONATION_INDEX_URL;
    this.namePlural = "payments";
    this.nameLowerCase = "payment";
  }
}
