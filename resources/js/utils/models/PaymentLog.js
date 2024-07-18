import Model from "./Model";
import { PAYMENT_LOG_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class PaymentLog extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PAYMENT_LOG_INDEX_URL;
    this.namePlural = "paymentlogs";
    this.nameLowerCase = "paymentlog";
  }
}
