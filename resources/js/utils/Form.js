import Error from "./Error";
import snakeCase from "lodash/snakeCase";
import Api from "./Api";

export default class Form {
  constructor(data) {
    this.originalData = data;

    for (let field in data) {
      this[field] = data[field];
    }

    this.errors = new Error();
  }

  data(edit = false) {
    let data = Object.assign({}, this);
    delete data.originalData;
    delete data.errors;

    let formData = new FormData();
    if (edit) formData.append("_method", "PUT");
    for (let field in data) {
      let value = data[field];
      if (value instanceof Array && value.length > 0) {
        this.dealWithArrayValues(snakeCase(field), value, formData);
      } else {
        formData.append(snakeCase(field), value);
      }
    }

    return formData;
  }

  reset() {
    this.errors.clear();

    for (let field in this.originalData) {
      this[field] = "";
    }
  }

  submit(method, url, edit = false) {
    this.errors.clear();

    return Api[method](url, this.data(edit));
    // return new Promise((resolve, reject) => {
    //   axios[method](url, this.data(edit))
    //     .then(response => resolve(response.data))
    //     .catch(error => reject(error.response.data));
    // });
  }

  post(url) {
    return this.submit("post", url);
  }

  put(url) {
    return this.submit("post", url, true);
  }

  dealWithArrayValues(field, value, formData) {
    for (let key in value) {
      let value2 = value[key];
      let newKey = field + "[" + snakeCase(key) + "]";
      if (typeof value2 === "object" && !(value2 instanceof File)) {
        this.dealWithArrayValues(newKey, value2, formData);
      } else {
        formData.append(newKey, value[key]);
      }
    }
  }
}
