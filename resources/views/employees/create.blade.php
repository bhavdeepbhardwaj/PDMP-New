@extends('layouts.app')

@section('title', 'Create Employee')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row mb-3">

            <div class="col-md-6">
                <h3>Create Employee</h3>
            </div>

            <div class="col-md-6 text-end">

                <a href="{{ route('employees.index') }}" class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back

                </a>

            </div>

        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        {{-- Create Form --}}
        <form action="{{ route('employees.store') }}" method="POST" autocomplete="off">

            @csrf

            @include('employees.partials.form')

        </form>

    </div>

@endsection
