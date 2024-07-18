import Model from "./Model";
import { POOL_INDEX_URL } from "@routes/admin";

export default class Pool extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = POOL_INDEX_URL;
        this.namePlural = "pools";
        this.nameLowerCase = "pool";
    }
}