import Model from "./Model";
import { DELIVERY_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Delivery extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = DELIVERY_INDEX_URL;
    this.namePlural = "deliveries";
    this.nameLowerCase = "delivery";
  }

  getLocationData(riderId) {
    return Api.get(this.indexUrl + "/locate/" + riderId);
  }
}
