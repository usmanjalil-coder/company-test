<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\LedgerStatement;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LedgerStatementController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = LedgerStatement::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('ledger-statements.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('ledger-statements.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('ledger-statements.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('ledger-statements', 'public');
        }

        LedgerStatement::create($validated);

        return redirect()->route('ledger-statements.index')->with('success', 'LedgerStatement created successfully.');
    }

    public function edit(LedgerStatement $ledger)
    {
        $clients = Client::all();
        return view('ledger-statements.edit', compact('ledger', 'clients'));
    }

    public function update(Request $request, LedgerStatement $ledger)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($ledger->file_path) {
                Storage::disk('public')->delete($ledger->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('ledger-statements', 'public');
        }

        $ledger->update($validated);

        return redirect()->route('ledger-statements.index')->with('success', 'LedgerStatement updated successfully.');
    }

    public function destroy(LedgerStatement $ledger)
    {
        if ($ledger->file_path) {
            Storage::disk('public')->delete($ledger->file_path);
        }
        $ledger->delete();

        return response()->json(['success' => 'LedgerStatement deleted successfully.']);
    }
}
