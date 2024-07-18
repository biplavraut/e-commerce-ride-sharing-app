import Model from "./Model";
import { OUTSTATION_TRIP_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class OutstationTrip extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = OUTSTATION_TRIP_INDEX_URL;
    this.namePlural = "outstation-trips";
    this.nameLowerCase = "outstation-trip";
  }

  getRiders() {
    return Api.get(this.indexUrl + "/get-rider");
  }
}
