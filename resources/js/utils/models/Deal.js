import Model from "./Model";
import { DEAL_INDEX_URL } from "@routes/admin";

export default class Deal extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = DEAL_INDEX_URL;
        this.namePlural = "deals";
        this.nameLowerCase = "deal";
    }
}