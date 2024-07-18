export default class Helpers {
  static focusFirstError(errors) {
    let element = document.querySelector(`[name="${errors.items[0].field}"]`);
    console.log(element);
    if (element) element.focus();
  }

  static focusId(id) {
    let element = document.getElementById(id);
    if (element) element.focus();
  }

  static nullToEmptyString(data, defaultValue = "") {
    return data ? data : defaultValue;
  }

  static cameraImage() {
    return "/images/camera.png";
  }

  static loadingImage() {
    return "/images/loading.gif";
  }

  static objToUrlParams(obj) {
    return Object.keys(obj)
      .map(key => encodeURIComponent(key) + "=" + encodeURIComponent(obj[key]))
      .join("&");
  }
}
