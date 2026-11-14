@extends('layouts.app')

@section('title', 'Student Management')
@section('page-heading', 'Student Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Student Management</h4>
            <p class="text-muted mb-0">Register and manage student information.</p>
        </div>

        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Add Student
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search student ID, name, or course..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All enrollment statuses</option>
                        @foreach(['active', 'inactive', 'graduated', 'transferred'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-dark flex-fill" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Course / Year</th>
                            <th>Guardians</th>
                            <th>RFID Cards</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($student->photo_path)
                                            <img src="{{ asset('storage/' . $student->photo_path) }}"
                                                 alt="Student photo"
                                                 class="rounded-circle"
                                                 style="width:42px;height:42px;object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                 style="width:42px;height:42px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                        @endif

                                        <div class="fw-semibold">{{ $student->full_name }}</div>
                                    </div>
                                </td>
                                <td>{{ $student->student_id }}</td>
                                <td>
                                    {{ $student->course ?? '—' }}
                                    <div class="small text-muted">
                                        {{ $student->year_level ?? '' }}
                                        {{ $student->section ? ' / ' . $student->section : '' }}
                                    </div>
                                </td>
                                <td>{{ $student->guardians->count() }}</td>
                                <td>{{ $student->rfidCards->where('status', 'active')->count() }}</td>
                                <td>
                                    <span class="badge {{ $student->enrollment_status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($student->enrollment_status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('students.show', $student) }}"
                                       class="btn btn-sm btn-outline-dark" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Archive this student record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Archive">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    No student records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection