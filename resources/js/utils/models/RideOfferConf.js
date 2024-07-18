import Model from "./Model";
import { RIDE_OFFER_CONF_INDEX_URL } from "@routes/admin";

export default class DefaultConf extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = RIDE_OFFER_CONF_INDEX_URL;
        this.namePlural = "ride-offers";
        this.nameLowerCase = "ride-offer";
    }
}