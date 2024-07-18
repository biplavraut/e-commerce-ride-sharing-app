import Model from "./Model";
import { WALLET_LOG_INDEX_URL } from "@routes/admin";

export default class WalletLog extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = WALLET_LOG_INDEX_URL;
        this.namePlural = "wallet-logs";
        this.nameLowerCase = "wallet-log";
    }
}