import Model from "./Model";
import { PRESCRIPTION_REQUEST_INDEX_URL } from "@routes/admin";

export default class PrescriptionRequest extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = PRESCRIPTION_REQUEST_INDEX_URL;
        this.namePlural = "prescription-requests";
        this.nameLowerCase = "prescription-request";
    }
}