{{-- resources/views/Dashboard/index.blade.php --}}

@extends('layouts.backend')

@section('title', 'Dashboard Management')

@section('content')

    <!-- Page -->

    <!-- End Page -->

@endsection

@push('scripts')
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script src="{{ asset('backend/global/js/Plugin/gmaps.js') }}"></script>
    <script src="{{ asset('backend/assets/examples/js/dashboard/v2.js') }}"></script>
@endpush
