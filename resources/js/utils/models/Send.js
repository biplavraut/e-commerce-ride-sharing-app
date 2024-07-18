import Model from "./Model";
import { SEND_INDEX_URL } from "@routes/admin";

export default class Send extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = SEND_INDEX_URL;
    this.namePlural = "sends";
    this.nameLowerCase = "send";
  }
}
