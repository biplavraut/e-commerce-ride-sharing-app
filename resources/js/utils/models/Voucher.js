import Model from "./Model";
import { VOUCHER_INDEX_URL } from "@routes/admin";

export default class Voucher extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = VOUCHER_INDEX_URL;
    this.namePlural = "vouchers";
    this.nameLowerCase = "voucher";
  }
}
