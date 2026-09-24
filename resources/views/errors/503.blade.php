{{-- resources/views/errors/503.blade.php --}}

@extends('layouts.error')

@section('title', '503 Error')

@section('content')
    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
        <div class="page-content vertical-align-middle">
            <i class="icon wb-settings icon-spin page-maintenance-icon" aria-hidden="true"></i>
            <h2>Under Maintenance</h2>
            <p>PLEASE GIVE US A MOMENT TO SORT THINGS OUT</p>

            <footer class="page-copyright">
                <p>Developed by NIC</p>
                <p>Copyright &copy; {{ date('Y') }}. All RIGHT RESERVED.</p>
                <div class="social">
                    <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                        <i class="icon bd-twitter" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                        <i class="icon bd-facebook" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                        <i class="icon bd-dribbble" aria-hidden="true"></i>
                    </a>
                </div>
            </footer>
        </div>
    </div>
    <!-- End Page -->
@endsection

@push('scripts')
@endpush
