@extends('vendor.layouts.master')

@section('content')

<div id="vendor-app">
    <app-container p-auth-user="{{ json_encode($authUser) }}" p-settings="{{ json_encode($company) }}"
        p-counts="{{ json_encode($counts) }}"></app-container>
</div>

@endsection