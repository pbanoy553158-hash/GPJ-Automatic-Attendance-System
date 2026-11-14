@extends('layouts.app')

@section('title', 'RFID Management')
@section('page-heading', 'RFID Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">RFID Management</h4>
            <p class="text-muted mb-0">Register and manage student RFID cards.</p>
        </div>
        <a href="{{ route('rfid.create') }}" class="btn btn-primary">
            <i class="bi bi-credit-card-2-front me-1"></i> Register RFID Card
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-6">
                    <input name="search" class="form-control"
                           placeholder="Search RFID UID or student..."
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
                    <a href="{{ route('rfid.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                            <th>RFID UID</th>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cards as $card)
                            <tr>
                                <td class="fw-semibold">{{ $card->uid }}</td>
                                <td>{{ $card->student?->full_name ?? 'Student unavailable' }}</td>
                                <td>{{ $card->student?->student_id ?? '—' }}</td>
                                <td>{{ $card->registered_at?->format('M d, Y h:i A') ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $card->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($card->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('rfid.edit', $card) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if($card->status === 'active')
                                        <form action="{{ route('rfid.destroy', $card) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Deactivate this RFID card?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    No RFID cards registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $cards->links() }}
        </div>
    </div>
</div>
@endsection