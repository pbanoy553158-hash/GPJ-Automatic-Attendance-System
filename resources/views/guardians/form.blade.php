@extends('layouts.app')

@section('title', isset($isEdit) && $isEdit ? 'Edit Guardian' : 'Add Guardian')
@section('page-heading', isset($isEdit) && $isEdit ? 'Edit Guardian' : 'Add Guardian')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('guardians.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Guardians
        </a>
        <h4 class="fw-bold mt-3">
            {{ isset($isEdit) && $isEdit ? 'Edit Guardian Information' : 'Register Guardian' }}
        </h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ isset($isEdit) && $isEdit ? route('guardians.update', $guardian) : route('guardians.store') }}">
        @csrf
        @if(isset($isEdit) && $isEdit)
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Guardian Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input name="full_name" class="form-control" required
                               value="{{ old('full_name', $guardian->full_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Primary Contact Number *</label>
                        <input name="contact_number" class="form-control" required
                               value="{{ old('contact_number', $guardian->contact_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secondary Contact Number</label>
                        <input name="secondary_contact_number" class="form-control"
                               value="{{ old('secondary_contact_number', $guardian->secondary_contact_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $guardian->email) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Notification Preferences</div>
            <div class="card-body">
                <div class="form-check mb-3">
                    <input type="checkbox" name="sms_consent" value="1"
                           class="form-check-input" id="smsConsent"
                           @checked(old('sms_consent', $guardian->sms_consent))>
                    <label for="smsConsent" class="form-check-label">
                        Guardian has consented to receive SMS notifications.
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="phone_verified" value="1"
                           class="form-check-input" id="phoneVerified"
                           @checked(old('phone_verified', $guardian->phone_verified))>
                    <label for="phoneVerified" class="form-check-label">
                        Phone number has been verified.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1"
                           class="form-check-input" id="guardianActive"
                           @checked(old('is_active', $guardian->exists ? $guardian->is_active : true))>
                    <label for="guardianActive" class="form-check-label">
                        Guardian account is active.
                    </label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Link to Students</div>
            <div class="card-body">
                <p class="text-muted small">
                    Select the students associated with this guardian. You can assign one primary student relationship here.
                </p>

                <div class="row g-2">
                    @foreach($students as $student)
                        <div class="col-md-6">
                            <div class="form-check border rounded p-3 ps-5">
                                <input class="form-check-input" type="checkbox"
                                       name="student_ids[]" value="{{ $student->id }}"
                                       id="student{{ $student->id }}"
                                       @checked(
                                           in_array(
                                               $student->id,
                                               old('student_ids', $guardian->exists
                                                   ? $guardian->students->pluck('id')->all()
                                                   : [])
                                           )
                                       )>
                                <label class="form-check-label" for="student{{ $student->id }}">
                                    <strong>{{ $student->full_name }}</strong>
                                    <span class="d-block small text-muted">
                                        {{ $student->student_id }} · {{ $student->course ?? 'No course' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($students->isEmpty())
                    <p class="text-muted mb-0">No active students are available to link.</p>
                @endif

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Relationship to Selected Students</label>
                        <input name="relationship" class="form-control"
                               placeholder="e.g. Mother, Father, Guardian"
                               value="{{ old('relationship') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Primary Student</label>
                        <select name="primary_student_id" class="form-select">
                            <option value="">No primary designation</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    @selected((string) old('primary_student_id',
                                        $guardian->exists
                                            ? optional($guardian->students->firstWhere('pivot.is_primary', true))->id
                                            : ''
                                    ) === (string) $student->id)>
                                    {{ $student->full_name }} ({{ $student->student_id }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            The primary designation applies only if that student is selected above.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('guardians.index') }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary">
                {{ isset($isEdit) && $isEdit ? 'Save Changes' : 'Register Guardian' }}
            </button>
        </div>
    </form>
</div>
@endsection