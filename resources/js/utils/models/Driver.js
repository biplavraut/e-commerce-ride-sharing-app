import Model from "./Model";
import { DRIVER_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Driver extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = DRIVER_INDEX_URL;
        this.namePlural = "drivers";
    }

    exportSheet() {
        return Api.get(this.indexUrl + "/excel-export");
    }

    getDrivers(val) {
        return Api.get(this.indexUrl + "/get-drivers?name=" + val);
    }

    verify(id) {
        return Api.get(this.indexUrl + "/verify-driver?id=" + id);
    }

    partiallyVerify(id) {
        return Api.get(this.indexUrl + "/partially-verify-driver?id=" + id);
    }

    getAll() {
        return Api.get(this.indexUrl);
    }
    getVerifiedRider() {
        return Api.get(this.indexUrl + "/verified-only");
    }

    getBlockedRider() {
        return Api.get(this.indexUrl + "/blocked-only");
    }

    getBlacklistedRider() {
        return Api.get(this.indexUrl + "/blacklisted-only");
    }

    getActiveRiders() {
        return Api.get(this.indexUrl + "/active-only");
    }

    getAssociatedRiders() {
        return Api.get(this.indexUrl + "/associated-only");
    }

    getLocationData(riderId) {
        return Api.get("/admin/trip/locate/" + riderId);
    }
}