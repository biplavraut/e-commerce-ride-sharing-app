import Model from "./Model";
import { ESEWA_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Esewa extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ESEWA_INDEX_URL;
    this.namePlural = "esewa";
    this.nameLowerCase = "esewa";
  }
}
