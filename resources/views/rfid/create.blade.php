@extends('layouts.app')

@section('title', 'Register RFID Card')
@section('page-heading', 'Register RFID Card')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('rfid.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to RFID Cards
        </a>

        <h4 class="fw-bold mt-3 mb-1">Register New RFID Card</h4>
        <p class="text-muted mb-0">
            Assign a unique RFID card UID to a registered student.
        </p>
    </div>

    @include('rfid.form', ['isEdit' => false])

</div>

@endsection