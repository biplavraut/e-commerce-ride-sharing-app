import Model from "./Model";
import { RIDING_FARE_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class RidingFare extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = RIDING_FARE_INDEX_URL;
    this.namePlural = "riding-fares";
  }

  deleteSurge(val) {
    return Api.get(this.indexUrl + "/delete-surge?surgeId=" + val);
  }
}
