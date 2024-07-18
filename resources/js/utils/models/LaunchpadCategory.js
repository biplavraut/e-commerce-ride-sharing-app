import Model from "./Model";
import {
  LAUNCHPAD_CATEGORY_INDEX_URL
} from "@routes/admin";
import Api from "../Api";

export default class LaunchpadCategory extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = LAUNCHPAD_CATEGORY_INDEX_URL;
    this.namePlural = "launchpad-categories";
    this.nameLowerCase = "launchpad-category";
  }

  getAll() {
    return Api.get(this.indexUrl + "/get-all");
  }
}