<template>
	<div class="form-group">
		<label>
			{{ label }}
			<small v-if="required">*</small>
		</label>

		<input :type="type"
		       class="form-control"
		       :class="{'error': hasError, 'valid': !hasError, datepicker, timepicker}"
		       :value="value"
		       v-bind="$attrs"
		       v-on="listeners" />

		<small class="text-danger"
		       v-if="hasError">* {{ errorText }}</small>
	</div>
</template>

<script>
export default {
	name: "InputText",

	inheritAttrs: false,

	props: {
		value: {},
		type: {
			type: String,
			default: "text"
		},
		required: {
			type: Boolean,
			default: false
		},
		datepicker: {
			type: Boolean,
			default: false
		},
		timepicker: {
			type: Boolean,
			default: false
		},
		label: {
			type: String,
			required: true
		},
		errorText: {
			type: String,
			default: ""
		},
		className: String
	},

	computed: {
		hasError() {
			return this.errorText !== "";
		},
		listeners() {
			let inputEvent = "input";

			if (this.datepicker || this.timepicker) {
				inputEvent = "blur";
			}

			return {
				...this.$listeners,
				[inputEvent]: event => this.$emit("input", event.target.value)
			};
		}
	},

	created() {
		if (this.datepicker) {
			initializeDatePicker();
		}

		if (this.timepicker) {
			initializeTimePicker();
		}
	}
};
</script>
