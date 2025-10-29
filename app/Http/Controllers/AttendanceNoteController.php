<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\AttendanceNote;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class AttendanceNoteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = AttendanceNote::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('attendance-notes.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('attendance-notes.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('attendance-notes.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('attendance-notes', 'public');
        }

        AttendanceNote::create($validated);

        return redirect()->route('attendance-notes.index')->with('success', 'AttendanceNote created successfully.');
    }

    public function edit(AttendanceNote $attendance)
    {
        $clients = Client::all();
        return view('attendance-notes.edit', compact( 'clients'));
    }

    public function update(Request $request, AttendanceNote $attendance)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($attendance->file_path) {
                Storage::disk('public')->delete($attendance->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('attendance-notes', 'public');
        }

        $attendance->update($validated);

        return redirect()->route('attendance-notes.index')->with('success', 'AttendanceNote updated successfully.');
    }

    public function destroy(AttendanceNote $attendance)
    {
        if ($attendance->file_path) {
            Storage::disk('public')->delete($attendance->file_path);
        }
        $attendance->delete();

        return response()->json(['success' => 'AttendanceNote deleted successfully.']);
    }
}
