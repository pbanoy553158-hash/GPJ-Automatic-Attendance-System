@extends('layouts.app')

@section('title', 'Student Details')
@section('page-heading', 'Student Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('students.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back to Students
            </a>
            <h4 class="fw-bold mt-3 mb-1">{{ $student->full_name }}</h4>
            <p class="text-muted mb-0">Student ID: {{ $student->student_id }}</p>
        </div>

        <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Student
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                @if($student->photo_path)
                    <img src="{{ asset('storage/' . $student->photo_path) }}"
                         class="rounded-circle"
                         style="width:90px;height:90px;object-fit:cover;"
                         alt="Student photo">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                         style="width:90px;height:90px;">
                        <i class="bi bi-person fs-1 text-secondary"></i>
                    </div>
                @endif

                <div>
                    <h5 class="fw-bold mb-1">{{ $student->full_name }}</h5>
                    <div class="text-muted">{{ $student->course ?? 'Course not specified' }}</div>
                    <span class="badge bg-success mt-2">
                        {{ ucfirst($student->enrollment_status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Student Information</div>
                <div class="card-body">
                    <p><strong>Gender:</strong> {{ $student->gender ?? '—' }}</p>
                    <p><strong>Date of Birth:</strong> {{ $student->date_of_birth?->format('F d, Y') ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $student->email ?? '—' }}</p>
                    <p><strong>Contact:</strong> {{ $student->contact_number ?? '—' }}</p>
                    <p><strong>Year Level:</strong> {{ $student->year_level ?? '—' }}</p>
                    <p><strong>Section:</strong> {{ $student->section ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Assigned Guardians</div>
                <div class="card-body">
                    @forelse($student->guardians as $guardian)
                        <div class="border-bottom py-2">
                            <strong>{{ $guardian->full_name }}</strong>
                            @if($guardian->pivot->is_primary)
                                <span class="badge bg-primary">Primary</span>
                            @endif
                            <div class="small text-muted">
                                {{ $guardian->pivot->relationship ?? 'Guardian' }}
                                · {{ $guardian->contact_number }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No guardian has been assigned.</p>
                    @endforelse
                    <a href="{{ route('guardians.create') }}" class="btn btn-sm btn-outline-primary mt-3">
                        Add Guardian
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">RFID Cards</div>
                <div class="card-body">
                    @forelse($student->rfidCards as $card)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $card->uid }}</span>
                            <span class="badge {{ $card->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($card->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No RFID card registered.</p>
                    @endforelse
                    <a href="{{ route('rfid.create', ['student_id' => $student->id]) }}"
                       class="btn btn-sm btn-outline-primary mt-3">
                        Register RFID Card
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Recent Attendance</div>
                <div class="card-body">
                    @forelse($student->attendance as $record)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $record->attendance_date?->format('M d, Y') }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No attendance records available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection