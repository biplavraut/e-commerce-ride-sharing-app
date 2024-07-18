import Model from "./Model";
import { ORDER_FEEDBACK_INDEX_URL } from "@routes/admin";

export default class OrderFeedback extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ORDER_FEEDBACK_INDEX_URL;
    this.namePlural = "order-feedbacks";
    this.nameLowerCase = "order-feedback";
  }
}
