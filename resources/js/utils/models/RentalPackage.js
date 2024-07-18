import Model from "./Model";
import { RENTAL_PACKAGE_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class RentalPackage extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = RENTAL_PACKAGE_INDEX_URL;
    this.namePlural = "rental-packages";
  }
}
