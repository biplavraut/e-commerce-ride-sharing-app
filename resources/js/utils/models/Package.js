import Model from "./Model";
import { PACKAGE_INDEX_URL } from "@routes/admin";

export default class Package extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = PACKAGE_INDEX_URL;
    this.namePlural = "packages";
    this.nameLowerCase = "package";
  }
}
