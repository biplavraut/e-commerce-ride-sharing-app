<template>
  <div class="form-group asdh-select">
    <label>{{ label }}</label>
    <select
      class="selectpicker"
      @change="emitEvent"
      data-style="select-with-transition"
      :data-live-search="dataLiveSearch"
      :data-size="dataSize"
      v-bind="$props"
    >
      <option value v-if="firstOption">Select {{ label }}</option>
      <option
        :data-tokens="dataTokens"
        :value="option.id"
        v-for="(option, index) in options"
        :key="index"
        :selected="option.id==value"
      >{{ option.name }}</option>
    </select>
    <div class="material-icons select-drop-down-arrow">keyboard_arrow_down</div>
  </div>
</template>

<script>
export default {
  name: "Select",

  props: {
    value: {},
    label: {
      type: String,
      required: false,
    },
    options: {
      type: Array,
      required: true,
    },
    dataLiveSearch: { default: true },
    dataTokens: { type: String, default: "" },
    dataSize: { default: 5 },
    firstOption: { type: Boolean, default: true },
  },

  methods: {
    emitEvent(event) {
      this.$emit("input", event.target.value);
    },
  },

  watch: {
    options(val) {
      refreshSelectPicker(200);
    },
  },

  mounted() {
    let self = this;
    $(document).on("keyup", ".bs-searchbox input", function () {
      self.$emit("typing", $(this).val());
    });
  },
};
</script>

<style scoped>
.inline {
  display: inline-block;
  margin-right: 10px;
  margin-top: 0;
  padding-bottom: 0;
}
</style>