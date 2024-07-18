import Model from "./Model";
import {NEWS_INDEX_URL} from "@routes/admin";

export default class News extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = NEWS_INDEX_URL;
    this.namePlural = 'news';
  }
}
