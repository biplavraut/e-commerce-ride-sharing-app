import Model from "./Model";
import { UTILITY_VOUCHER_INDEX_URL } from "@routes/admin";

export default class UtilityVoucher extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = UTILITY_VOUCHER_INDEX_URL;
        this.namePlural = "utility-vouchers";
        this.nameLowerCase = "utility-voucher";
    }
}