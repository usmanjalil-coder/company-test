<?php

namespace App\Http\Controllers;

use App\Models\AuthorityLetter;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class AuthorityLetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $letters = AuthorityLetter::with('client')->select(['id', 'client_id', 'content', 'date', 'file_path']);
            return DataTables::of($letters)
                ->addColumn('client_name', fn($row) => $row->client->name)
                ->addColumn('actions', fn($row) => '<a href="' . route('authority-letters.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                                              <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('authority-letters.index');
    }

    public function create()
    {
        $clients = Client::all();
        return view('authority-letters.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'content' => 'required|string',
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('authority_letters', 'public');
        }

        AuthorityLetter::create($validated);

        return redirect()->route('authority-letters.index')->with('success', 'Authority Letter created successfully.');
    }

    public function edit(AuthorityLetter $authorityLetter)
    {
        $clients = Client::all();
        return view('authority-letters.edit', compact('authorityLetter', 'clients'));
    }

    public function update(Request $request, AuthorityLetter $authorityLetter)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'content' => 'required|string',
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($authorityLetter->file_path) {
                Storage::disk('public')->delete($authorityLetter->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('authority_letters', 'public');
        }

        $authorityLetter->update($validated);

        return redirect()->route('authority-letters.index')->with('success', 'Authority Letter updated successfully.');
    }

    public function destroy(AuthorityLetter $authorityLetter)
    {
        if ($authorityLetter->file_path) {
            Storage::disk('public')->delete($authorityLetter->file_path);
        }
        $authorityLetter->delete();

        return response()->json(['success' => 'Authority Letter deleted successfully.']);
    }
}