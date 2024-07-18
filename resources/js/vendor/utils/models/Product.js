import Model from "./Model";
import { PRODUCT_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class Product extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = PRODUCT_INDEX_URL;
        this.namePlural = "products";
    }

    exportSheet() {
        return Api.get(this.indexUrl + "/excel-export");
    }

    getCategory() {
        return Api.get("/vendor/product-category/get-all");
    }

    getSubCategory(val) {
        return Api.get(this.indexUrl + "/get-subcategory?category=" + val);
    }

    getProducts(val) {
        return Api.get(this.indexUrl + "/get-products?name=" + val);
    }

    getTag(val) {
        return Api.get(this.indexUrl + "/get-tags?name=" + val);
    }

    getUnits(val) {
        return Api.get(this.indexUrl + "/get-units?category=" + val);
    }

    getProductOptions(val) {
        return Api.get("/vendor/products/options?id=" + val);
    }

    getIsProductHidden(val) {
        return Api.get(this.indexUrl + "/is-hidden?id=" + val);
    }
}