import Model from "./Model";
import { ORDER_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Order extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ORDER_INDEX_URL;
    this.namePlural = "orders";
  }

  getAll() {
    return Api.get(this.indexUrl);
  }
  getAcceptedOrder() {
    return Api.get(this.indexUrl + "/accepted-order");
  }

  getTakeawayList() {
    return Api.get(this.indexUrl+'/takeaway-list');
  }
}
