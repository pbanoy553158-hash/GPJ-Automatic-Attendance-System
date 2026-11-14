@extends('layouts.app')

@section('title', 'Edit Guardian')
@section('page-heading', 'Edit Guardian')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('guardians.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Guardians
        </a>

        <h4 class="fw-bold mt-3 mb-1">Edit Guardian Information</h4>
        <p class="text-muted mb-0">
            Update guardian contact information and linked students.
        </p>
    </div>

    @include('guardians.form', ['isEdit' => true])

</div>

@endsection