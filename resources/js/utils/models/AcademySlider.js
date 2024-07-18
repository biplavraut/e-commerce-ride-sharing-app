import Model from "./Model";
import { ACADEMY_SLIDER_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class AcademySlider extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ACADEMY_SLIDER_INDEX_URL;
    this.namePlural = "academy-sliders";
    this.nameLowerCase = "academy-slider";
  }
}
