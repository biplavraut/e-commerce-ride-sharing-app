import Model from "./Model";
import { ACADEMY_CONTENT_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class AcademyContent extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ACADEMY_CONTENT_INDEX_URL;
    this.namePlural = "academy-contents";
    this.nameLowerCase = "academy-content";
  }
}
