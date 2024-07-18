<template>
  <div class="form-group">
    <label>
      {{ label }}
      <small v-if="required">*</small>
    </label>

    <vue-editor :value="value"
                :editorToolbar="customToolbar"
                :editorOptions="editorSettings"
                v-bind="$attrs"
                v-on="listeners"></vue-editor>

    <small class="text-danger"
           v-if="errorText!==''">* {{ errorText }}
    </small>
  </div>
</template>

<script>
  import {VueEditor, Quill} from 'vue2-editor'
  import {ImageDrop} from 'quill-image-drop-module'

  Quill.register('modules/imageDrop', ImageDrop);

  export default {
    name: "QuillEditor",

    components: {
      VueEditor
    },

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

    data() {
      return {
        customToolbar: [
          [{'font': [false, 'serif', 'monospace']}],
          [{'header': [1, 2, 3, 4, 5, 6, false]}],
          ['bold', 'italic', 'underline', /*'strike'*/],
          [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
          ['blockquote', 'code-block'],
          [{'script': 'sub'}, {'script': 'super'}],
          [{'list': 'ordered'}, {'list': 'bullet'}, {'list': 'check'}],
          [{'indent': '-1'}, {'indent': '+1'}],
          [{'color': []}, {'background': []}],
          ['link', 'image'],
          ['clean'],
          /*[{ 'direction': 'rtl' }],*/
        ],
        editorSettings: {
          modules: {
            imageDrop: true,
          }
        },
      }
    },

    computed: {
      listeners() {
        return this.$listeners;
      }
    }
  }
</script>

<style scoped>

</style>