import vuexStore from "@stores/store";

export default class Cache {
	constructor(moduleName) {
		this.moduleName = moduleName;
	}

	store(list) {
		this.updateList(list);
		this.enable();
	}

	updateList(list) {
		vuexStore.commit(this.moduleName + "/setList", list);
	}

	enable() {
		vuexStore.commit(this.moduleName + "/localAccess", true);
	}

	invalidate() {
		vuexStore.commit(this.moduleName + "/localAccess", false);
		this.updateList({});
	}

	hasData() {
		return vuexStore.getters[this.moduleName + "/localAccess"];
	}

	getData() {
		return vuexStore.getters[this.moduleName + "/list"];
	}
}
