import Model from "./Model";
import { ORDER_OFFER_CONF_INDEX_URL } from "@routes/admin";

export default class DefaultConf extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = ORDER_OFFER_CONF_INDEX_URL;
        this.namePlural = "order-offers";
        this.nameLowerCase = "order-offer";
    }
}