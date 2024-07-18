import Model from "./Model";
import {
  SLIDER_INDEX_URL
} from "@routes/admin";
import Api from "../Api";


export default class Slider extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = SLIDER_INDEX_URL;
    this.namePlural = "sliders";
    this.nameLowerCase = "slider";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }
}