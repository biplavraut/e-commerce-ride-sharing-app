import Model from "./Model";
import { JUNCTION_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class DeliveryJunction extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = JUNCTION_INDEX_URL;
    this.namePlural = "delivery-junctions";
    this.nameLowerCase = "delivery-junction";
  }

  getAll() {
    return Api.get(this.indexUrl + "/list");
  }
}
