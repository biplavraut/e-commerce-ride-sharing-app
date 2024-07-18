import Model from "./Model";
import { CART_INDEX_URL } from "@routes/admin";

export default class Cart extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = CART_INDEX_URL;
        this.namePlural = "carts";
        this.nameLowerCase = "cart";
    }
}