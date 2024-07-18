import Model from "./Model";
import { CATEGORY_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Category extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = CATEGORY_INDEX_URL;
    this.namePlural = "categories";
  }

  exportSheet() {
    return Api.get(this.indexUrl + "/excel-export");
  }

  getAll() {
    return Api.get(this.indexUrl + "/get-all");
  }
}
