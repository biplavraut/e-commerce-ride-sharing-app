import Model from "./Model";
import { LAYOUT_MANAGER_INDEX_URL } from "@routes/admin";
import Api from "../Api";

export default class LayoutManager extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = LAYOUT_MANAGER_INDEX_URL;
    this.namePlural = "layout-managers";
    this.nameLowerCase = "layout-manager";
  }

  getData(val) {
    return Api.get(this.indexUrl + "/by-service?id=" + val);
  }

  getModelList() {
    return Api.get(this.indexUrl + "/model-list");
  }

  getModelIdList(val, val1) {
    return Api.get(
      this.indexUrl + "/model-id-list?model=" + val + "&service=" + val1
    );
  }
}
