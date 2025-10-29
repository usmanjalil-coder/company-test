<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\CoveringLetter;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class CoveringLetterController extends Controller
{public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = CoveringLetter::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('covering-letters.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('covering-letters.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('covering-letters.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('covering-letters', 'public');
        }

        CoveringLetter::create($validated);

        return redirect()->route('covering-letters.index')->with('success', 'CoveringLetter created successfully.');
    }

    public function edit(CoveringLetter $coveringLetter)
    {
        $clients = Client::all();
        return view('covering-letters.edit', compact('clients'));
    }

    public function update(Request $request, CoveringLetter $coveringLetter)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($coveringLetter->file_path) {
                Storage::disk('public')->delete($coveringLetter->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('covering-letters', 'public');
        }

        $coveringLetter->update($validated);

        return redirect()->route('covering-letters.index')->with('success', 'CoveringLetter updated successfully.');
    }

    public function destroy(CoveringLetter $coveringLetter)
    {
        if ($coveringLetter->file_path) {
            Storage::disk('public')->delete($coveringLetter->file_path);
        }
        $coveringLetter->delete();

        return response()->json(['success' => 'CoveringLetter deleted successfully.']);
    }
}
