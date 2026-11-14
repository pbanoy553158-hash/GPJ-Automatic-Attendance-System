@extends('layouts.app')

@section('title', 'Edit RFID Card')
@section('page-heading', 'Edit RFID Card')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('rfid.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to RFID Cards
        </a>

        <h4 class="fw-bold mt-3 mb-1">Edit RFID Registration</h4>
        <p class="text-muted mb-0">
            Update the RFID card assignment or activation status.
        </p>
    </div>

    @include('rfid.form', ['isEdit' => true])

</div>

@endsection