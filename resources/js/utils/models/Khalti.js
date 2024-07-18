import Model from "./Model";
import { KHALTI_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Khalti extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = KHALTI_INDEX_URL;
    this.namePlural = "khalti";
    this.nameLowerCase = "khalti";
  }
}
