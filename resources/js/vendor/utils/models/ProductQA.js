import Model from "./Model";
import { PRODUCT_QAS_INDEX_URL } from "@routes/admin";

export default class ProductQA extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PRODUCT_QAS_INDEX_URL;
    this.namePlural = "qas";
  }
}
