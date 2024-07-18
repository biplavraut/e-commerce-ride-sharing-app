<template>
  <div class="modal fade action-delete make-darker"
       id="index-delete-model"
       tabindex="-1"
       role="dialog"
       aria-labelledby="myModalLabel"
       aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-hidden="true">
            <i class="material-icons">clear</i>
          </button>
          <h4 class="modal-title text-center">Are you sure?</h4>
        </div>

        <div class="modal-body text-center"
             style="padding:20px 10px;">
          <p>You will not be able to recover you data in the future.</p>
        </div>

        <div class="modal-footer text-center">
          <button type="submit"
                  class="btn btn-danger">Yes Delete It</button>
          <button type="button"
                  class="btn btn-success"
                  data-dismiss="modal">No Don't Delete It</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
	name: "DeletePopUp"
};
</script>

<style scoped>
</style>