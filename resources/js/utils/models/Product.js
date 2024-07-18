import Model from "./Model";
import { PRODUCT_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Product extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_INDEX_URL;
    this.namePlural = "products";
    this.nameLowerCase = "product";
  }

  exportSheet() {
    return Api.get(this.indexUrl + "/excel-export");
  }

  getCategory(vendor) {
    return Api.get("/admin/product-category/get-root?vendor=" + vendor);
  }

  getSubCategory(val) {
    return Api.get(this.indexUrl + "/get-subcategory?category=" + val);
  }

  getProducts(val) {
    return Api.get(this.indexUrl + "/get-products?name=" + val);
  }

  verifiedProducts() {
    return Api.get(this.indexUrl + "/verified-only");
  }

  getTag(val) {
    return Api.get(this.indexUrl + "/get-tags?name=" + val);
  }

  getUnits(val) {
    return Api.get(this.indexUrl + "/get-units?category=" + val);
  }

  getProductOptions(val) {
    return Api.get(this.indexUrl + "/options?vendorId=" + val);
  }

  verify(id) {
    return Api.get(this.indexUrl + "/verify-product?id=" + id);
  }
  reset() {
    return Api.get(this.indexUrl);
  }

  verifyMultiple(ids) {
    return Api.get(this.indexUrl + "/verify-multiple?id=" + ids);
  }
}
