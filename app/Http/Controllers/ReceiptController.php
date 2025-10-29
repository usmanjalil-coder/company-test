<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Receipt::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('receipts.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('receipts.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('receipts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('receipts', 'public');
        }

        Receipt::create($validated);

        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
    }

    public function edit(Receipt $receipt)
    {
        $clients = Client::all();
        return view('receipts.edit', compact('clients'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($receipt->file_path) {
                Storage::disk('public')->delete($receipt->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('receipts', 'public');
        }

        $receipt->update($validated);

        return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully.');
    }

    public function destroy(Receipt $receipt)
    {
        if ($receipt->file_path) {
            Storage::disk('public')->delete($receipt->file_path);
        }
        $receipt->delete();

        return response()->json(['success' => 'Receipt deleted successfully.']);
    }
}
