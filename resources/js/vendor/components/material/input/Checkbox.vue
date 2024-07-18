<template>
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input type="checkbox" :checked="value" v-bind="$attrs" :required="required" v-on="listeners">
        {{ label }}
      </label>
    </div>
  </div>
</template>

<script>
export default {
	name: "Checkbox",

	inheritAttrs: false,

	props: {
		value: {
			type: Boolean,
			default: false
		},
		required: {
			type: Boolean,
			default: false
		},
		label: {
			type: String,
			default: ""
		}
	},

	computed: {
		listeners() {
			return {
				...this.$listeners,
				input: event => this.$emit("input", event.target.checked)
			};
		}
	}
};
</script>
