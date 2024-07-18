import Model from "./Model";
import { PRODUCT_REVIEW_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class ProductReview extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_REVIEW_INDEX_URL;
    this.namePlural = "reviews";
  }

  verify(id) {
    return Api.get("/vendor/product/verify-review?id=" + id);
  }
}
