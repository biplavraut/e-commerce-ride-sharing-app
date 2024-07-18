import Model from "./Model";
import {
    REPORT_HOME
} from "@routes/admin";

export default class Report extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = REPORT_HOME;
        this.namePlural = "reports";
        this.nameLowerCase = "report";
    }
}