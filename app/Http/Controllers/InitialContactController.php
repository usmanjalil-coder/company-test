<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\InitialContact;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InitialContactController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = InitialContact::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('initial-contacts.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('initial-contacts.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('initial-contacts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('initial-contacts', 'public');
        }

        InitialContact::create($validated);

        return redirect()->route('initial-contacts.index')->with('success', 'InitialContact created successfully.');
    }

    public function edit(InitialContact $initial)
    {
        $clients = Client::all();
        return view('initial-contacts.edit', compact('clients'));
    }

    public function update(Request $request, InitialContact $initial)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($initial->file_path) {
                Storage::disk('public')->delete($initial->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('initial-contacts', 'public');
        }

        $initial->update($validated);

        return redirect()->route('initial-contacts.index')->with('success', 'InitialContact updated successfully.');
    }

    public function destroy(InitialContact $initial)
    {
        if ($initial->file_path) {
            Storage::disk('public')->delete($initial->file_path);
        }
        $initial->delete();

        return response()->json(['success' => 'InitialContact deleted successfully.']);
    }
}
