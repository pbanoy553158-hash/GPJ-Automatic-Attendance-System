@extends('layouts.app')

@section('title', isset($isEdit) && $isEdit ? 'Edit RFID Card' : 'Register RFID Card')
@section('page-heading', isset($isEdit) && $isEdit ? 'Edit RFID Card' : 'Register RFID Card')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('rfid.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to RFID Cards
        </a>
        <h4 class="fw-bold mt-3">
            {{ isset($isEdit) && $isEdit ? 'Edit RFID Registration' : 'Register New RFID Card' }}
        </h4>
        <p class="text-muted">Assign a unique RFID UID to a student.</p>
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST"
                  action="{{ isset($isEdit) && $isEdit ? route('rfid.update', $card) : route('rfid.store') }}">
                @csrf
                @if(isset($isEdit) && $isEdit)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Student *</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Select student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                @selected((string) old('student_id',
                                    $card->student_id ?: request('student_id')
                                ) === (string) $student->id)>
                                {{ $student->full_name }} — {{ $student->student_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">RFID UID *</label>
                    <input name="uid" class="form-control" required
                           autocomplete="off"
                           placeholder="Scan or enter RFID card UID"
                           value="{{ old('uid', $card->uid) }}">
                    <small class="text-muted">
                        The UID is normalized to uppercase and spaces are removed.
                        Each UID can only be registered once.
                    </small>
                </div>

                @if(isset($isEdit) && $isEdit)
                    <div class="mb-4">
                        <label class="form-label">Card Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" @selected(old('status', $card->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $card->status) === 'inactive')>Inactive</option>
                        </select>
                    </div>
                @endif

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rfid.index') }}" class="btn btn-light">Cancel</a>
                    <button class="btn btn-primary">
                        {{ isset($isEdit) && $isEdit ? 'Save Changes' : 'Register RFID Card' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection