import Model from "./Model";
import { VENDOR_OPTION_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class VendorOption extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = VENDOR_OPTION_INDEX_URL;
    this.namePlural = "vendor-options";
    this.nameLowerCase = "vendor-option";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }

  getList() {
    return Api.get(this.indexUrl + "/category-list");
  }
}
