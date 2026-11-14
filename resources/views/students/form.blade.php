@extends('layouts.app')

@section('title', isset($isEdit) && $isEdit ? 'Edit Student' : 'Add Student')
@section('page-heading', isset($isEdit) && $isEdit ? 'Edit Student' : 'Add Student')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('students.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Students
        </a>
        <h4 class="fw-bold mt-3 mb-1">
            {{ isset($isEdit) && $isEdit ? 'Edit Student Information' : 'Register New Student' }}
        </h4>
        <p class="text-muted mb-0">Enter the student's personal and enrollment details.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ isset($isEdit) && $isEdit ? route('students.update', $student) : route('students.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($isEdit) && $isEdit)
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold py-3">Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student ID *</label>
                        <input name="student_id" class="form-control" required
                               value="{{ old('student_id', $student->student_id) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">First Name *</label>
                        <input name="first_name" class="form-control" required
                               value="{{ old('first_name', $student->first_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input name="middle_name" class="form-control"
                               value="{{ old('middle_name', $student->middle_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Last Name *</label>
                        <input name="last_name" class="form-control" required
                               value="{{ old('last_name', $student->last_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select gender</option>
                            @foreach(['Male', 'Female', 'Other'] as $gender)
                                <option value="{{ $gender }}"
                                    @selected(old('gender', $student->gender) === $gender)>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control"
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $student->email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control"
                               value="{{ old('contact_number', $student->contact_number) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Student Photo</label>
                        <input type="file" name="photo" class="form-control"
                               accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">JPG, PNG, or WEBP; maximum 2 MB.</small>

                        @if($student->photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $student->photo_path) }}"
                                     alt="Current student photo"
                                     style="width:80px;height:80px;object-fit:cover;"
                                     class="rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold py-3">Enrollment Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Course</label>
                        <input name="course" class="form-control"
                               value="{{ old('course', $student->course) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Year Level</label>
                        <input name="year_level" class="form-control"
                               placeholder="e.g. 1st Year"
                               value="{{ old('year_level', $student->year_level) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Section</label>
                        <input name="section" class="form-control"
                               value="{{ old('section', $student->section) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Enrollment Status *</label>
                        <select name="enrollment_status" class="form-select" required>
                            @foreach(['active', 'inactive', 'graduated', 'transferred'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('enrollment_status', $student->enrollment_status ?: 'active') === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Registration Date</label>
                        <input type="date" name="registration_date" class="form-control"
                               value="{{ old('registration_date', $student->registration_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>
                {{ isset($isEdit) && $isEdit ? 'Save Changes' : 'Register Student' }}
            </button>
        </div>
    </form>
</div>
@endsection