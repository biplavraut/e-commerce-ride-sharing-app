import Model from "./Model";
import { VENDOR_OPTION_CATEGORY_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class VendorOptionCategory extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = VENDOR_OPTION_CATEGORY_INDEX_URL;
    this.namePlural = "vendor-option-categories";
    this.nameLowerCase = "vendor-option-category";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }
}
