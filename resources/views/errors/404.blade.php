{{-- resources/views/errors/404.blade.php --}}

@extends('layouts.error')

@section('title', '404 Error')

@section('content')
    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <div class="page-content vertical-align-middle">
            <header>
                <h1 class="animation-slide-top">404</h1>
                <p>Page Not Found !</p>
            </header>
            <p class="error-advise">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>
            <a class="btn btn-primary btn-round" href="/">GO TO HOME PAGE</a>

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
