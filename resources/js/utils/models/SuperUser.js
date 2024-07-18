import Model from "./Model";
import { SUPER_USER_INDEX_URL } from "@routes/admin";

export default class SuperUser extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = SUPER_USER_INDEX_URL;
        this.namePlural = "super-users";
        this.nameLowerCase = "super-user";
    }

    exportSheet() {
        return Api.get(this.indexUrl + "/excel-export");
    }
}