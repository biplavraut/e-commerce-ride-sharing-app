import Model from "./Model";
import { CAMPAIGN_INDEX_URL } from "@routes/admin";

export default class Campaign extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = CAMPAIGN_INDEX_URL;
    this.namePlural = "campaigns";
    this.nameLowerCase = "campaign";
  }
}
