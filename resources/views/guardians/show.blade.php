@extends('layouts.app')

@section('title', 'Guardian Details')
@section('page-heading', 'Guardian Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('guardians.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back to Guardians
            </a>
            <h4 class="fw-bold mt-3 mb-1">{{ $guardian->full_name }}</h4>
            <p class="text-muted mb-0">Guardian information and linked students</p>
        </div>
        <a href="{{ route('guardians.edit', $guardian) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Guardian
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Contact Information</div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $guardian->full_name }}</p>
                    <p><strong>Contact:</strong> {{ $guardian->contact_number }}</p>
                    <p><strong>Secondary:</strong> {{ $guardian->secondary_contact_number ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $guardian->email ?? '—' }}</p>
                    <p><strong>SMS Consent:</strong> {{ $guardian->sms_consent ? 'Yes' : 'No' }}</p>
                    <p><strong>Phone Verified:</strong> {{ $guardian->phone_verified ? 'Yes' : 'No' }}</p>
                    <p class="mb-0"><strong>Status:</strong> {{ $guardian->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Linked Students</div>
                <div class="card-body">
                    @forelse($guardian->students as $student)
                        <div class="border-bottom py-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $student->full_name }}</strong>
                                @if($student->pivot->is_primary)
                                    <span class="badge bg-primary">Primary</span>
                                @endif
                            </div>
                            <div class="small text-muted">
                                {{ $student->student_id }} ·
                                {{ $student->course ?? 'Course not specified' }}
                            </div>
                            <div class="small">
                                Relationship: {{ $student->pivot->relationship ?? 'Guardian' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">This guardian is not linked to any students.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection