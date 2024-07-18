import Model from "./Model";
import { RENTAL_TRIP_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class RentalTrip extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = RENTAL_TRIP_INDEX_URL;
    this.namePlural = "rental-trips";
  }

  getTrips(val) {
    return Api.get(this.indexUrl + "/get-trip?name=" + val);
  }

  getRiders() {
    return Api.get(this.indexUrl + "/get-rider");
  }
}
