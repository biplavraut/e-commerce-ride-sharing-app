import Model from "./Model";
import { AD_INDEX_URL } from "@routes/admin";

export default class Ad extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = AD_INDEX_URL;
    this.namePlural = "ads";
    this.nameLowerCase = "ad";
  }
}
