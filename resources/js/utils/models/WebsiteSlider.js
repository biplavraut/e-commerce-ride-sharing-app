import Model from "./Model";
import {
  WEBSITE_SLIDER_INDEX_URL
} from "@routes/admin";
import Api from "../Api";


export default class WebsiteSlider extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = WEBSITE_SLIDER_INDEX_URL;
    this.namePlural = "website-sliders";
    this.nameLowerCase = "website-slider";
  }

}