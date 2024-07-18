import Api from '@utils/Api'
import Helpers from '@utils/Helpers'

export default class Model {
  constructor(data = {}) {
    for (let key in data) {
      this[key] = data[key];
    }
    this.nameLowerCase = this.constructor.name.toLowerCase();
    this.cache = new ModelCache(this.nameLowerCase);
    this.indexUrl = '';
  }

  show(id) {
    return Api.get(this.indexUrl + "/" + id);
  }

  getPaginatedList(filters = {}) {
    filters = Helpers.objToUrlParams(filters);

    return new Promise((resolve, reject) => {
      if (this.cache.hasData()) {
        resolve(this.cache.getData());
      } else {
        Api.get(this.indexUrl + "?" + filters)
          .then(data => {
            this.cache.store(data);
            resolve(data);
          })
          .catch(data => reject(data))
      }
    })
  }

  getPaginatedListUncached(filters = {}) {
    filters = Helpers.objToUrlParams(filters);

    return new Promise((resolve, reject) => {
      Api.get(this.indexUrl + "?" + filters)
        .then(data => {
          this.cache.store(data);
          resolve(data);
        })
        .catch(data => reject(data))
    })
  }

  store(data) {
    return Api.post(this.indexUrl, data);
  }

  update(id, data) {
    return Api.post(this.indexUrl + "/" + id, data);
  }

  delete(id) {
    return Api.delete(this.indexUrl + "/" + id);
  }

  deleteMultiple(ids) {
    return Api.post(this.indexUrl + "/delete-multiple", ids);
  }

  changeOrder(payload) {
    let data = payload.map(data => data.id);

    return Api.post(this.indexUrl + "/change-order", data);
  }
}
