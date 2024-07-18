import Model from "./Model";
import { PRODUCT_OPTION_CATEGORY_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class ProductOptionCategory extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_OPTION_CATEGORY_INDEX_URL;
    this.namePlural = "product-option-categories";
    this.nameLowerCase = "product-option-category";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }
}
