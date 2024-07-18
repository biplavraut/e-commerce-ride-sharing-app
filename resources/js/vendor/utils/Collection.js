import _ from "lodash";

class Collection {
	constructor(data) {
		this.data = data;
	}
	init(data) {
		return new Collection(data);
	}
	get() {
		return this.data;
	}
	values() {
		return Object.values(this.data);
	}
	count() {
		return _.size(this.data);
	}
	sum(callback = false) {
		return callback ? _.sumBy(this.data, callback) : _.sum(this.data);
	}
	average(callback = false) {
		let sum = callback ? this.sum(callback) : this.sum();

		return sum / this.count();
	}
	avg(callback = false) {
		return this.average(callback);
	}
	chunk(size) {
		return this.init(_.chunk(this.data, size));
	}
	flatten(depth = false) {
		let flattened = depth
			? _.flattenDepth(this.data, depth)
			: _.flattenDeep(this.data);

		return this.init(flattened);
	}
	concat(data) {
		return this.init(this.data.concat(data));
	}
	first() {
		return _.first(this.data);
	}
	last() {
		return _.last(this.data);
	}
	nth() {
		return _.nth(this.data);
	}
	reduce(callback) {
		return _.reduce(callback);
	}
	diff(data) {
		return this.init(_.difference(this.data, data));
	}
	unique(callback = false) {
		let data = callback ? _.uniqBy(this.data, callback) : _.uniq(this.data);

		return this.init(data);
	}
	indexOf(callback) {
		return _.findIndex(this.data, callback);
	}
	forEach(callback) {
		return this.init(_.forEach(this.data, callback));
	}
	map(callback) {
		return this.init(_.map(this.data, callback));
	}
	groupBy(callback) {
		return this.init(_.groupBy(this.data, callback));
	}
	sortBy(types, orders) {
		return this.init(_.orderBy(this.data, types, orders));
	}
	reject(callback) {
		return this.init(_.reject(this.data, callback));
	}
	filter(callback) {
		return this.init(_.filter(this.data, callback));
	}
	random(size = 1) {
		return size > 1
			? this.init(_.sampleSize(this.data, size))
			: _.sample(this.data);
	}
	shuffle() {
		return this.init(_.shuffle(this.data));
	}
	has(callback) {
		return _.some(this.data, callback);
	}
	push(data) {
		this.data.push(data);
		return this.init(this.data);
	}
	pop() {
		this.data.pop();

		return this.init(this.data);
	}
	shift() {
		this.data.shift();

		return this.init(this.data);
	}
	unshift(data) {
		this.data.unshift(data);

		return this.init(this.data);
	}
	reverse() {
		return this.init(_.reverse(this.data));
	}
}

export default Collection;
