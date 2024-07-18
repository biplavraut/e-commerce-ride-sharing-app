import Model from "./Model";
import { WALLET_PAYMENT_LOG_INDEX_URL } from "@routes/admin";

export default class WalletPaymentLog extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = WALLET_PAYMENT_LOG_INDEX_URL;
        this.namePlural = "wallet-payment-logs";
        this.nameLowerCase = "wallet-payment-log";
    }
}