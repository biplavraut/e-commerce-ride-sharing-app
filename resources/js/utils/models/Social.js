import Model from "./Model"
import {SOCIAL_INDEX_URL} from "@routes/admin";

export default class Social extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = SOCIAL_INDEX_URL;
    this.namePlural = 'socials';
  }
}
