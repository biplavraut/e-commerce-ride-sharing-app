<template>
  <div class="form-group">
    <label>
      {{ label }}
      <small v-if="required">*</small>
    </label>
    <textarea class="form-control"
              v-bind="$attrs"
              v-html="value"
              v-on="listeners"></textarea>
    <small class="text-danger"
           v-if="errorText!==''">* {{ errorText }}
    </small>
  </div>
</template>

<script>
  export default {
    name: "InputTextarea",

    inheritAttrs: false,

    props: {
      value: {
        type: String
      },
      required: {
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
      }
    },

    computed: {
      listeners() {
        return {
          ...this.$listeners,
          input: event => this.$emit("input", event.target.value)
        };
      }
    }
  };
</script>
