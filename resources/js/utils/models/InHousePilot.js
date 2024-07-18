import Model from "./Model";
import { RIDER_LOG_INDEX_URL } from "@routes/admin";

export default class InHousePilot extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = RIDER_LOG_INDEX_URL;
    this.namePlural = "inhouse-pilots";
    this.nameLowerCase = "inhouse-pilot";
  }
}
