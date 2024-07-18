import Vue from "vue";
import kebabCase from "lodash/kebabCase";

const filesToIgnore = ["./TinymceInit.vue"];
const COMPONENT_PREFIX = "app";

const requireComponent = require.context(".", true, /\.vue$/);
// const requireComponent = require.context(".", true, /\.vue$/, 'lazy');

requireComponent
	.keys()
	.filter(filePath => !filesToIgnore.includes(filePath))
	.forEach(filePath => {
		const filePathSplitted = filePath.split("/");
		const arrLen = filePathSplitted.length;
		let fileName = filePathSplitted[arrLen - 1];

		let kebabCaseFileName = kebabCase(fileName.replace(/\.vue$/, ""));

		fileName = COMPONENT_PREFIX + "-" + kebabCaseFileName;

		if (filePathSplitted[1] === "input") {
			fileName = "input-" + kebabCaseFileName;
		}

		const fileFull = requireComponent(filePath);

		Vue.component(fileName, fileFull.default || fileFull);
		// Vue.component(fileName, () => import(/* webpackChunkName: "./js/admin/components/[request]" */ `${filePath}`));
	});
