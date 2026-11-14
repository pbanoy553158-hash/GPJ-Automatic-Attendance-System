@extends('layouts.app')

@section('title', 'Add Guardian')
@section('page-heading', 'Add Guardian')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('guardians.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Guardians
        </a>

        <h4 class="fw-bold mt-3 mb-1">Register New Guardian</h4>
        <p class="text-muted mb-0">
            Enter guardian contact information and link students.
        </p>
    </div>

    @include('guardians.form', ['isEdit' => false])

</div>

@endsection