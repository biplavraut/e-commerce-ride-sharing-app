import Model from "./Model";
import { PARTNER_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Partner extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PARTNER_INDEX_URL;
    this.namePlural = "partners";
    this.nameLowerCase = "partner";
  }
}
