import Model from "./Model";
import {
  USER_INDEX_URL
} from "@routes/admin";

export default class User extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = USER_INDEX_URL;
    this.namePlural = "users";
    this.nameLowerCase = "user";
  }

  exportSheet() {
    return Api.get(this.indexUrl + "/excel-export");
  }
}