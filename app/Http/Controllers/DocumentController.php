<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Document::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('documents.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('documents.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('documents.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        Document::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    public function edit(Document $documents)
    {
        $clients = Client::all();
        return view('documents.edit', compact( 'clients'));
    }

    public function update(Request $request, Document $documents)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($documents->file_path) {
                Storage::disk('public')->delete($documents->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        $documents->update($validated);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $documents)
    {
        if ($documents->file_path) {
            Storage::disk('public')->delete($documents->file_path);
        }
        $documents->delete();

        return response()->json(['success' => 'Document deleted successfully.']);
    }
}
