import Model from "./Model";
import { GLOBAL_NOTIFICATION_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class GlobalNotification extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = GLOBAL_NOTIFICATION_INDEX_URL;
    this.namePlural = "global-notifications";
    this.nameLowerCase = "global-notification";
  }

  sendAgain(id) {
    return Api.get(this.indexUrl+"/send-now/" + id);
}
}
