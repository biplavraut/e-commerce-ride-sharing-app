import Model from "./Model";
import {
  LAUNCHPAD_INDEX_URL
} from "@routes/admin";
import Api from "../Api";


export default class Launchpad extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = LAUNCHPAD_INDEX_URL;
    this.namePlural = "launchpads";
    this.nameLowerCase = "launchpad";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-category?id=" + val);
  }


}