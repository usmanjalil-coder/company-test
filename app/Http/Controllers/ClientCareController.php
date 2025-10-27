<?php

namespace App\Http\Controllers;

use App\Models\ClientCare;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientCareController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clientCare = ClientCare::with('client')->select(['id', 'client_id', 'care_notes', 'date']);
            return DataTables::of($clientCare)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('client-care.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('client-care.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('client-care.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'care_notes' => 'required|string',
            'date' => 'required|date',
        ]);

        ClientCare::create($validated);

        return redirect()->route('client-care.index')->with('success', 'Client Care created successfully.');
    }

    // edit, update, destroy methods similar to AuthorityLetterController
}