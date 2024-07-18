import Model from "./Model";
import { VENDOR_RATING_INDEX_URL } from "@routes/admin";
import Api from "../../vendor/utils/Api";

export default class VendorReview extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = VENDOR_RATING_INDEX_URL;
    this.namePlural = "vendor-reviews";
  }

  verify(id) {
    return Api.get(this.indexUrl + "/verify?reviewId=" + id);
  }
}
