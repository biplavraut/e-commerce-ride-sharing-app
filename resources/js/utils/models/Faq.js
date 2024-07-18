import Model from "./Model";
import { FAQ_INDEX_URL } from "@routes/admin";

export default class Faq extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = FAQ_INDEX_URL;
    this.namePlural = "faqs";
    this.nameLowerCase = "faq";
  }
}
