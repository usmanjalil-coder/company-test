<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\FollowupLetter;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FollowupLetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = FollowupLetter::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('followup-letters.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('followup-letters.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('followup-letters.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('followup-letters', 'public');
        }

        FollowupLetter::create($validated);

        return redirect()->route('followup-letters.index')->with('success', 'FollowupLetter created successfully.');
    }

    public function edit(FollowupLetter $followupLetter)
    {
        $clients = Client::all();
        return view('followup-letters.edit', compact('clients'));
    }

    public function update(Request $request, FollowupLetter $followupLetter)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($followupLetter->file_path) {
                Storage::disk('public')->delete($followupLetter->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('followup-letters', 'public');
        }

        $followupLetter->update($validated);

        return redirect()->route('followup-letters.index')->with('success', 'FollowupLetter updated successfully.');
    }

    public function destroy(FollowupLetter $followupLetter)
    {
        if ($followupLetter->file_path) {
            Storage::disk('public')->delete($followupLetter->file_path);
        }
        $followupLetter->delete();

        return response()->json(['success' => 'FollowupLetter deleted successfully.']);
    }
}
