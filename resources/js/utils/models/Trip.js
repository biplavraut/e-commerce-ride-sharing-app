import Model from "./Model";
import { TRIP_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Trip extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = TRIP_INDEX_URL;
    this.namePlural = "trips";
    this.nameLowerCase = "trip";
  }

  getLocationData(riderId) {
    return Api.get(this.indexUrl + "/locate/" + riderId);
  }
  getData() {
    return Api.get(this.indexUrl);
  }

  getAccident() {
    return Api.get(this.indexUrl + "/get-accident-trips");
  }

  getDispute() {
    return Api.get(this.indexUrl + "/get-dispute-trips");
  }
}
