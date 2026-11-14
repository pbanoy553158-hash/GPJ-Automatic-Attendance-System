<?php

namespace App\Http\Controllers;

use App\Models\RfidCard;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RfidCardController extends Controller
{
    public function index(Request $request)
    {
        $cards = RfidCard::with('student')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery->where('student_id', 'like', "%{$search}%")
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('rfid.index', compact('cards'));
    }

    public function create()
    {
        return view('rfid.create', [
            'card' => new RfidCard(),
            'students' => Student::where('enrollment_status', 'active')
                ->orderBy('last_name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'uid' => ['required', 'string', 'max:255', 'unique:rfid_cards,uid'],
        ]);

        $validated['uid'] = strtoupper(preg_replace('/\s+/', '', $validated['uid']));
        $validated['status'] = 'active';
        $validated['registered_at'] = now();

        // Check again after normalizing the UID.
        if (RfidCard::where('uid', $validated['uid'])->exists()) {
            return back()->withInput()->withErrors([
                'uid' => 'This RFID UID is already registered.',
            ]);
        }

        RfidCard::create($validated);

        return redirect()->route('rfid.index')
            ->with('success', 'RFID card registered successfully.');
    }

    public function edit(RfidCard $rfid)
    {
        return view('rfid.edit', [
            'card' => $rfid,
            'students' => Student::where('enrollment_status', 'active')
                ->orderBy('last_name')
                ->get(),
        ]);
    }

    public function update(Request $request, RfidCard $rfid)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'uid' => [
                'required', 'string', 'max:255',
                Rule::unique('rfid_cards', 'uid')->ignore($rfid->id),
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['uid'] = strtoupper(preg_replace('/\s+/', '', $validated['uid']));

        if (
            RfidCard::where('uid', $validated['uid'])
                ->where('id', '!=', $rfid->id)
                ->exists()
        ) {
            return back()->withInput()->withErrors([
                'uid' => 'This RFID UID is already registered.',
            ]);
        }

        $validated['deactivated_at'] = $validated['status'] === 'inactive'
            ? ($rfid->deactivated_at ?? now())
            : null;

        $rfid->update($validated);

        return redirect()->route('rfid.index')
            ->with('success', 'RFID card information updated successfully.');
    }

    public function destroy(RfidCard $rfid)
    {
        $rfid->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);

        return redirect()->route('rfid.index')
            ->with('success', 'RFID card deactivated. Its registration history was retained.');
    }
}