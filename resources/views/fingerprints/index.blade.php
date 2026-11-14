
@extends('layouts.app')

@section('title', 'Fingerprint Management')
@section('page-heading', 'Fingerprint Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Fingerprint Templates</h4>
            <p class="text-muted mb-0">
                Manage AS608 fingerprint slot assignments.
            </p>
        </div>

        <a href="{{ route('fingerprints.create') }}" class="btn btn-primary">
            <i class="bi bi-fingerprint me-1"></i>
            Add Fingerprint Mapping
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Device ID</th>
                            <th>Sensor Slot</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $template->student?->full_name ?? 'Unknown student' }}
                                    </strong>
                                </td>
                                <td>{{ $template->student?->student_id ?? '—' }}</td>
                                <td>{{ $template->device_id }}</td>
                                <td>{{ $template->template_id }}</td>
                                <td>
                                    {{ $template->registered_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>
                                <td>
                                    @if($template->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('fingerprints.edit', $template) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if($template->status === 'active')
                                        <form action="{{ route('fingerprints.destroy', $template) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Deactivate this fingerprint mapping?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-person-x"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    No fingerprint templates have been registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $templates->links() }}
        </div>
    </div>
</div>
@endsection
