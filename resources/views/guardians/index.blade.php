@extends('layouts.app')

@section('title', 'Guardian Management')
@section('page-heading', 'Guardian Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Guardian Management</h4>
            <p class="text-muted mb-0">Manage parent and guardian contact information.</p>
        </div>
        <a href="{{ route('guardians.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Add Guardian
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-6">
                    <input name="search" class="form-control"
                           placeholder="Search name, contact, or email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-dark flex-fill">Search</button>
                    <a href="{{ route('guardians.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                            <th>Guardian</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Linked Students</th>
                            <th>SMS Consent</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guardians as $guardian)
                            <tr>
                                <td class="fw-semibold">{{ $guardian->full_name }}</td>
                                <td>{{ $guardian->contact_number }}</td>
                                <td>{{ $guardian->email ?? '—' }}</td>
                                <td>{{ $guardian->students->count() }}</td>
                                <td>
                                    <span class="badge {{ $guardian->sms_consent ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $guardian->sms_consent ? 'Allowed' : 'Not allowed' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $guardian->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $guardian->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('guardians.show', $guardian) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('guardians.edit', $guardian) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('guardians.destroy', $guardian) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Archive this guardian record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    No guardian records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $guardians->links() }}
        </div>
    </div>
</div>
@endsection