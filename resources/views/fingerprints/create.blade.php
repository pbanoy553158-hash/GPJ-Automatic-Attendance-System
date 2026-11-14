
@extends('layouts.app')

@section('title', 'Add Fingerprint')
@section('page-heading', 'Add Fingerprint')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('fingerprints.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Fingerprints
        </a>
        <h4 class="fw-bold mt-3 mb-1">Register Fingerprint Mapping</h4>
        <p class="text-muted mb-0">
            Enter the sensor slot after the AS608 enrollment succeeds.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('fingerprints.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Select student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                @selected(old('student_id') == $student->id)>
                                {{ $student->student_id }} — {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">ESP32 Device</label>
                    <select name="device_id" class="form-select" required>
                        <option value="">Select device</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->device_id }}"
                                @selected(old('device_id') == $device->device_id)>
                                {{ $device->name }} ({{ $device->device_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('device_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">AS608 Template Slot ID</label>
                    <input type="number"
                           name="template_id"
                           min="1"
                           max="65535"
                           class="form-control"
                           value="{{ old('template_id') }}"
                           required>
                    <div class="form-text">
                        Use the ID stored in the AS608 sensor, not the Laravel record ID.
                    </div>
                    @error('template_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    Enroll the finger on the AS608 first. Only save the mapping
                    after the sensor confirms successful enrollment.
                </div>

                <button class="btn btn-primary">
                    Save Fingerprint Mapping
                </button>
                <a href="{{ route('fingerprints.index') }}" class="btn btn-light">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
