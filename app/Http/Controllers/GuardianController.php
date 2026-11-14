<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GuardianController extends Controller
{
    public function index(Request $request)
    {
        $guardians = Guardian::with('students')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('guardians.index', compact('guardians'));
    }

    public function create()
    {
        return view('guardians.create', [
            'guardian' => new Guardian(),
            'students' => Student::where('enrollment_status', 'active')
                ->orderBy('last_name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:30'],
            'secondary_contact_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'sms_consent' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'distinct', 'exists:students,id'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'primary_student_id' => ['nullable', 'integer', 'exists:students,id'],
        ]);

        $guardian = DB::transaction(function () use ($validated) {
            $guardian = Guardian::create([
                'full_name' => $validated['full_name'],
                'contact_number' => $validated['contact_number'],
                'secondary_contact_number' => $validated['secondary_contact_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'sms_consent' => $validated['sms_consent'] ?? false,
                'phone_verified' => $validated['phone_verified'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            $this->syncStudents($guardian, $validated);

            return $guardian;
        });

        return redirect()->route('guardians.index')
            ->with('success', 'Guardian registered successfully.');
    }

    public function show(Guardian $guardian)
    {
        $guardian->load('students');

        return view('guardians.show', compact('guardian'));
    }

    public function edit(Guardian $guardian)
    {
        $guardian->load('students');

        return view('guardians.edit', [
            'guardian' => $guardian,
            'students' => Student::where('enrollment_status', 'active')
                ->orderBy('last_name')
                ->get(),
        ]);
    }

    public function update(Request $request, Guardian $guardian)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:30'],
            'secondary_contact_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'sms_consent' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'distinct', 'exists:students,id'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'primary_student_id' => ['nullable', 'integer', 'exists:students,id'],
        ]);

        DB::transaction(function () use ($guardian, $validated) {
            $guardian->update([
                'full_name' => $validated['full_name'],
                'contact_number' => $validated['contact_number'],
                'secondary_contact_number' => $validated['secondary_contact_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'sms_consent' => $validated['sms_consent'] ?? false,
                'phone_verified' => $validated['phone_verified'] ?? false,
                'is_active' => $validated['is_active'] ?? false,
            ]);

            $this->syncStudents($guardian, $validated);
        });

        return redirect()->route('guardians.show', $guardian)
            ->with('success', 'Guardian information updated successfully.');
    }

    public function destroy(Guardian $guardian)
    {
        $guardian->delete();

        return redirect()->route('guardians.index')
            ->with('success', 'Guardian record archived successfully.');
    }

    private function syncStudents(Guardian $guardian, array $data): void
    {
        $studentIds = $data['student_ids'] ?? [];
        $relationship = $data['relationship'] ?? 'Guardian';
        $primaryStudentId = $data['primary_student_id'] ?? null;

        $syncData = [];

        foreach ($studentIds as $studentId) {
            $syncData[$studentId] = [
                'relationship' => $relationship,
                'is_primary' => (int) $primaryStudentId === (int) $studentId,
            ];
        }

        $guardian->students()->sync($syncData);

        if ($primaryStudentId && in_array((int) $primaryStudentId, array_map('intval', $studentIds), true)) {
            DB::table('student_guardian')
                ->where('student_id', $primaryStudentId)
                ->where('guardian_id', '!=', $guardian->id)
                ->update(['is_primary' => false]);
        }
    }
}