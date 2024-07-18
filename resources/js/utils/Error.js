import isEmpty from "lodash/isEmpty";

export default class Error {
	constructor() {
		this.errors = {};
	}

	initialize(errors) {
		this.errors = errors;
	}

	/**
	 * Check if both server and client have any errors
	 * @param {boolean} clientHasErrors
	 */
	any(clientHasErrors) {
		return !isEmpty(this.errors) || clientHasErrors;
	}

	has(key) {
		return this.errors.hasOwnProperty(key);
	}

	get(key) {
		if (this.has(key)) {
			return this.errors[key][0];
		}
	}

	clear(key) {
		if (key) {
			if (typeof key === "string") {
				this.delete(key);
			} else if (typeof key === "object") {
				key.forEach(key => {
					this.delete(key);
				});
			}
		} else {
			this.errors = {};
		}
	}

	delete(key) {
		if (this.has(key)) {
			delete this.errors[key];
		}
	}
}
