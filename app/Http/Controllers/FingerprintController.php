<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\FingerprintTemplate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FingerprintController extends Controller
{
    /**
     * Display all fingerprint templates.
     */
    public function index(): View
    {
        $templates = FingerprintTemplate::with('student')
            ->orderByDesc('registered_at')
            ->paginate(15);

        return view('fingerprints.index', compact('templates'));
    }

    /**
     * Display the fingerprint registration form.
     */
    public function create(): View
    {
        $students = Student::query()
            ->where('enrollment_status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $devices = Device::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('fingerprints.create', compact(
            'students',
            'devices'
        ));
    }

    /**
     * Store a fingerprint template mapping.
     *
     * The fingerprint must already have been enrolled
     * successfully in the AS608 sensor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where('enrollment_status', 'active'),
            ],

            'device_id' => [
                'required',
                'string',
                Rule::exists('devices', 'device_id')
                    ->where('is_active', true),
            ],

            'template_id' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
        ]);

        $slotExists = FingerprintTemplate::query()
            ->where('device_id', $validated['device_id'])
            ->where('template_id', $validated['template_id'])
            ->where('status', 'active')
            ->exists();

        if ($slotExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'template_id' =>
                        'This AS608 fingerprint slot is already assigned.',
                ]);
        }

        FingerprintTemplate::create([
            'student_id' => $validated['student_id'],
            'device_id' => $validated['device_id'],
            'template_id' => $validated['template_id'],
            'status' => 'active',
            'registered_at' => now(),
            'deactivated_at' => null,
        ]);

        return redirect()
            ->route('fingerprints.index')
            ->with(
                'success',
                'Fingerprint template registered successfully.'
            );
    }

    /**
     * Display the edit form.
     */
    public function edit(
        FingerprintTemplate $fingerprint
    ): View {
        $students = Student::query()
            ->where('enrollment_status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $devices = Device::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('fingerprints.edit', compact(
            'fingerprint',
            'students',
            'devices'
        ));
    }

    /**
     * Update a fingerprint template mapping.
     */
    public function update(
        Request $request,
        FingerprintTemplate $fingerprint
    ): RedirectResponse {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where('enrollment_status', 'active'),
            ],

            'device_id' => [
                'required',
                'string',
                Rule::exists('devices', 'device_id')
                    ->where('is_active', true),
            ],

            'template_id' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
        ]);

        $slotExists = FingerprintTemplate::query()
            ->where('device_id', $validated['device_id'])
            ->where('template_id', $validated['template_id'])
            ->where('status', 'active')
            ->where('id', '!=', $fingerprint->id)
            ->exists();

        if ($slotExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'template_id' =>
                        'This AS608 fingerprint slot is already assigned.',
                ]);
        }

        $fingerprint->update([
            'student_id' => $validated['student_id'],
            'device_id' => $validated['device_id'],
            'template_id' => $validated['template_id'],
        ]);

        return redirect()
            ->route('fingerprints.index')
            ->with(
                'success',
                'Fingerprint mapping updated successfully.'
            );
    }

    /**
     * Deactivate a fingerprint mapping.
     */
    public function destroy(
        FingerprintTemplate $fingerprint
    ): RedirectResponse {
        $fingerprint->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);

        return redirect()
            ->route('fingerprints.index')
            ->with(
                'success',
                'Fingerprint mapping deactivated successfully.'
            );
    }
}
