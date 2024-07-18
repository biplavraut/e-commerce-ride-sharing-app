import Model from "./Model";
import { PREMIUM_PLACE_INDEX_URL } from "@routes/admin";
import Api from "../Api";


export default class PremiumPlace extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PREMIUM_PLACE_INDEX_URL;
    this.namePlural = "premium-places";
  }

  getPlaces(val) {
    return Api.get(this.indexUrl + "/get-place?name=" + val);
  }
}
