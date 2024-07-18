import Model from "./Model";
import {
    ORDER_INDEX_URL
} from "@routes/admin";
import Api from "../Api";

export default class Order extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = ORDER_INDEX_URL;
        this.namePlural = "orders";
        this.nameLowerCase = "order";
    }

    getAll($key = null) {
        if ($key != null) {
            return Api.get(ORDER_INDEX_URL + "?key=" + $key);
        }
        return Api.get(this.indexUrl);
    }
}