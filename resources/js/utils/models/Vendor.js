import Model from "./Model";
import { VENDOR_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Vendor extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = VENDOR_INDEX_URL;
    }

    getVendors(val) {
        return Api.get(this.indexUrl + "/get-vendors?name=" + val);
    }

    getData(val, hidden, isInActive) {
        return Api.get(this.indexUrl + "/by-service?id=" + val + '&isHidden=' + hidden + '&isInActive=' + isInActive);
    }

    verify(id, serviceId, settlement) {
        return Api.get(
            this.indexUrl +
            "/verify-me?id=" +
            id +
            "&service=" +
            serviceId +
            "&settlement=" +
            settlement
        );
    }
    getList() {
        return Api.get(this.indexUrl + "/list");
    }

    getHealthList() {
        return Api.get(this.indexUrl + "/by-service?id=30");
    }

    getVendorOptions(vendor, service) {
        return Api.get(
            this.indexUrl + "/options?vendorId=" + vendor + "&serviceId=" + service
        );
    }
}