<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\OutcomeLetter;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OutcomeLetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = OutcomeLetter::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('outcome-letters.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('outcome-letters.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('outcome-letters.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('outcome-letters', 'public');
        }

        OutcomeLetter::create($validated);

        return redirect()->route('outcome-letters.index')->with('success', 'OutcomeLetter created successfully.');
    }

    public function edit(OutcomeLetter $outcome)
    {
        $clients = Client::all();
        return view('outcome-letters.edit', compact( 'clients'));
    }

    public function update(Request $request, OutcomeLetter $outcome)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($outcome->file_path) {
                Storage::disk('public')->delete($outcome->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('outcome-letters', 'public');
        }

        $outcome->update($validated);

        return redirect()->route('outcome-letters.index')->with('success', 'OutcomeLetter updated successfully.');
    }

    public function destroy(OutcomeLetter $outcome)
    {
        if ($outcome->file_path) {
            Storage::disk('public')->delete($outcome->file_path);
        }
        $outcome->delete();

        return response()->json(['success' => 'OutcomeLetter deleted successfully.']);
    }
}
