@extends('layouts.app')

@section('title', 'Edit Student')
@section('page-heading', 'Edit Student')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('students.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Students
        </a>

        <h4 class="fw-bold mt-3 mb-1">Edit Student Information</h4>
        <p class="text-muted mb-0">
            Update the student's personal and enrollment information.
        </p>
    </div>

    @include('students.form', ['isEdit' => true])

</div>

@endsection