import Model from "./Model";
import {
  PRODUCT_CATEGORY_INDEX_URL
} from "@routes/admin";
import Api from "../Api";

export default class ProductCategory extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_CATEGORY_INDEX_URL;
    this.namePlural = "product-categories";
  }

  exportSheet() {
    return Api.get(this.indexUrl + "/excel-export");
  }

  getAll() {
    return Api.get(this.indexUrl + "/get-all");
  }

  getRoot() {
    return Api.get(this.indexUrl + "/get-root");
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-category?id=" + val);
  }
}