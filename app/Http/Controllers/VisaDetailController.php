<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\VisaDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VisaDetailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = VisaDetail::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('visa-details.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('visa-details.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('visa-details.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('visa-details', 'public');
        }

        VisaDetail::create($validated);

        return redirect()->route('visa-details.index')->with('success', 'VisaDetail created successfully.');
    }

    public function edit(VisaDetail $visa)
    {
        $clients = Client::all();
        return view('visa-details.edit', compact('clients'));
    }

    public function update(Request $request, VisaDetail $visa)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($visa->file_path) {
                Storage::disk('public')->delete($visa->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('visa-details', 'public');
        }

        $visa->update($validated);

        return redirect()->route('visa-details.index')->with('success', 'VisaDetail updated successfully.');
    }

    public function destroy(VisaDetail $visa)
    {
        if ($visa->file_path) {
            Storage::disk('public')->delete($visa->file_path);
        }
        $visa->delete();

        return response()->json(['success' => 'VisaDetail deleted successfully.']);
    }
}
