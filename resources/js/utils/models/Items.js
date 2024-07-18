import Model from "./Model";
import { ITEMS_INDEX_URL } from "@routes/admin";

export default class Items extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = ITEMS_INDEX_URL;
        this.namePlural = "Items";
        this.nameLowerCase = "Item";
    }
}