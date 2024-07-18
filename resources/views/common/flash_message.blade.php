<style>
  .asdh-frontend-alert-message {
    position : fixed;
    width    : 50%;
    top      : 20px;
    left     : 25%;
    z-index  : 10;
  }
</style>
@if(session('success_message'))
  <div class="alert alert-success hide-after-some-seconds asdh-frontend-alert-message">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p>{{session('success_message')}}</p>
  </div>
@elseif(session('failure_message'))
  <div class="alert alert-warning hide-after-some-seconds asdh-frontend-alert-message">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p>{{session('failure_message')}}</p>
  </div>
@endif

@if(count($errors))
  <div class="alert alert-danger hide-after-some-seconds asdh-frontend-alert-message">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @foreach($errors->all() as $error)
      <p>{{$error}}</p>
    @endforeach
  </div>
@endif
