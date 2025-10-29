<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Invoice::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('invoices.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('invoices.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('invoices.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('invoices', 'public');
        }

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invocie)
    {
        $clients = Client::all();
        return view('invoices.edit', compact( 'clients'));
    }

    public function update(Request $request, Invoice $invocie)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($invocie->file_path) {
                Storage::disk('public')->delete($invocie->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('invoices', 'public');
        }

        $invocie->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invocie)
    {
        if ($invocie->file_path) {
            Storage::disk('public')->delete($invocie->file_path);
        }
        $invocie->delete();

        return response()->json(['success' => 'Invoice deleted successfully.']);
    }
}
