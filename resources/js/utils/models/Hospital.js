import Model from "./Model";
import { HOSPITAL_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Hospital extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = HOSPITAL_INDEX_URL;
        this.namePlural = "hospitals";
        this.nameLowerCase = "hospital";
    }

    getAll() {
        return Api.get(this.indexUrl + "/list");
    }
}