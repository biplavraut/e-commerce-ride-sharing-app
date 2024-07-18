import Model from "./Model";
import { ORDER_RETURN_INDEX_URL } from "@routes/admin";

export default class OrderReturn extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = ORDER_RETURN_INDEX_URL;
        this.namePlural = "order-returns";
        this.nameLowerCase = "order-return";
    }
}