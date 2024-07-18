import Model from "./Model";
import { DINEIN_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Dinein extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = DINEIN_INDEX_URL;
    this.namePlural = "dinein";
    this.nameLowerCase = "dinein";
  }
}
