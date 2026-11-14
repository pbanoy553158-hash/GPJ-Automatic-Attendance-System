@extends('layouts.app')

@section('title', 'Add Student')
@section('page-heading', 'Add Student')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('students.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Students
        </a>

        <h4 class="fw-bold mt-3 mb-1">Register New Student</h4>
        <p class="text-muted mb-0">
            Enter the student's personal and enrollment information.
        </p>
    </div>

    @include('students.form', ['isEdit' => false])

</div>

@endsection