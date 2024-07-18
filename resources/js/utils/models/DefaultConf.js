import Model from "./Model";
import { CONF_INDEX_URL } from "@routes/admin";

export default class DefaultConf extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = CONF_INDEX_URL;
    this.namePlural = "confs";
    this.nameLowerCase = "conf";
  }
}
