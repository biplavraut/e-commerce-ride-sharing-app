import Model from "./Model";
import {
  UNIT_INDEX_URL
} from "@routes/admin";

export default class Unit extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = UNIT_INDEX_URL;
    this.namePlural = "units";
    this.nameLowerCase = "unit";
  }
}