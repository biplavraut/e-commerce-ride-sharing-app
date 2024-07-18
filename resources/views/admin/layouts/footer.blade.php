<!--   Core JS Files   -->
<script src="{{ adminAssetsUrl('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ adminAssetsUrl('js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ adminAssetsUrl('js/material.min.js') }}" type="text/javascript"></script>
<script src="{{ adminAssetsUrl('js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
<!-- Library for adding dinamically elements -->
<script src="{{ adminAssetsUrl('js/arrive.min.js') }}" type="text/javascript"></script>
<!-- Promise Library for SweetAlert2 working on IE -->
<script src="{{ adminAssetsUrl('js/es6-promise-auto.min.js') }}"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="{{ adminAssetsUrl('js/moment.min.js') }}"></script>
{{-- Data Table --}}
{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.25/b-1.7.1/b-html5-1.7.1/b-print-1.7.1/fh-3.1.9/r-2.2.9/datatables.min.js"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{ adminAssetsUrl('js/jquery.datatables.js') }}"></script>

<!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="{{ adminAssetsUrl('js/jquery.bootstrap-wizard.js') }}"></script>
<!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
<script src="{{ adminAssetsUrl('js/bootstrap-notify.js') }}"></script>
<!--   Sharrre Library    -->
{{--<script src="{{ adminAssetsUrl('js/jquery.sharrre.js') }}"></script>--}}
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="{{ adminAssetsUrl('js/bootstrap-datetimepicker.js') }}"></script>
<!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="{{ adminAssetsUrl('js/jquery.select-bootstrap.js') }}"></script>
<!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{ adminAssetsUrl('js/jquery.tagsinput.js') }}"></script>
<!-- Material Dashboard javascript methods -->
<script src="{{ adminAssetsUrl('js/material-dashboard.js?v=1.2.0') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
<!-- 'classic' theme -->
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.es5.min.js"></script>

{{-- Google Map --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByFMucI3xTPpuGscSQr1BcxK5KAhdiUnM"></script>

<!-- My javascript -->
<script src="{{  myAsset('/plugins/nestable.js')  }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.1/tinymce.min.js"></script>--}}

<script src="{{ myAsset('/js/common.js') }}"></script>
<script src="{{ myAsset('/js/asdh_admin.js') }}"></script>

<script src="{{ myAsset('/js/manifest.js') }}"></script>
<script src="{{ myAsset('/js/vendor.js') }}"></script>
<script src="{{ myAsset('/js/app.js') }}"></script>