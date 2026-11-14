@extends('layouts.app')

@section('title', $module['title'])
@section('page-heading', $module['title'])

@section('content')

    <div class="dashboard-heading">
        <div>
            <h2>{{ $module['title'] }}</h2>

            <p>
                {{ $module['description'] }}
            </p>
        </div>
    </div>

    <div class="content-card module-placeholder">

        <div class="placeholder-icon">
            <i class="bi bi-tools"></i>
        </div>

        <h3>{{ $module['title'] }}</h3>

        <p>{{ $module['description'] }}</p>

        <span class="phase-badge">
            Planned for {{ $module['phase'] }}
        </span>

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-orange">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </div>

    </div>

@endsection