import Model from "./Model";
import { ADD_SERVICE_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class AddService extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ADD_SERVICE_INDEX_URL;
    this.namePlural = "additional-services";
    this.nameLowerCase = "additional-service";
  }
}
