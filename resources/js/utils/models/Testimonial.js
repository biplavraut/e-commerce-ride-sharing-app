import Model from "./Model"
import {TESTIMONIAL_INDEX_URL} from "@routes/admin";

export default class Testimonial extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = TESTIMONIAL_INDEX_URL;
    this.namePlural = 'testimonials';
  }
}
