<template>
  <div class="form-group">
    <label>{{ label }}
      <small v-if="required">*</small>
    </label>
    <input type="text" class="form-control" :placeholder="placeholder" readonly>
    <input type="file"
           class="form-control"
           :class="className"
           :required="required"
           v-bind="$attrs"
           @change="emitEvent"/>
    <small class="text-danger" v-if="errorText!==''">* {{ errorText }}</small>
  </div>
</template>

<script>
  export default {
    name: "InputFile",

    inheritAttrs: false,

    props: {
      value    : {},
      required : {
        type   : Boolean,
        default: false
      },
      label    : {
        type    : String,
        required: true
      },
      className: {
        type: String
      },
      errorText: {
        type   : String,
        default: ""
      }
    },

    methods: {
      emitEvent(event) {
        let imageFile = event.target.files[0] || "";

        this.$emit("input", imageFile);
      }
    },

    computed: {
      placeholder() {
        return this.$attrs['placeholder']
      }
    }
  }
</script>

<style scoped>

</style>