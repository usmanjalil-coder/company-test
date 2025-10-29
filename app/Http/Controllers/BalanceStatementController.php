<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\BalanceStatement;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class BalanceStatementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = BalanceStatement::with('client')->select(['id', 'client_id', /* other fields */]);
            return DataTables::of($records)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('balance-statements.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('balance-statements.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('balance-statements.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048', // If file upload needed
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('balance-statements', 'public');
        }

        BalanceStatement::create($validated);

        return redirect()->route('balance-statements.index')->with('success', 'BalanceStatement created successfully.');
    }

    public function edit(BalanceStatement $balance_statement)
    {
        $clients = Client::all();
        return view('balance-statements.edit', compact('balance_$balance_statement', 'clients'));
    }

    public function update(Request $request, BalanceStatement $balance_statement)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            // Add other validation rules
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($balance_statement->file_path) {
                Storage::disk('public')->delete($balance_statement->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('balance-statements', 'public');
        }

        $balance_statement->update($validated);

        return redirect()->route('balance-statements.index')->with('success', 'BalanceStatement updated successfully.');
    }

    public function destroy(BalanceStatement $balance_statement)
    {
        if ($balance_statement->file_path) {
            Storage::disk('public')->delete($balance_statement->file_path);
        }
        $balance_statement->delete();

        return response()->json(['success' => 'BalanceStatement deleted successfully.']);
    }
}
