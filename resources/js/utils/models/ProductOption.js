import Model from "./Model";
import { PRODUCT_OPTION_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class ProductOption extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_OPTION_INDEX_URL;
    this.namePlural = "product-options";
    this.nameLowerCase = "product-option";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }

  getList() {
    return Api.get(this.indexUrl + "/category-list");
  }
}
