<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companies = Company::select(['id', 'company_name', 'company_address', 'contact_number', 'email_address', 'solicitor_name', 'regulated_by', 'company_reg_number']);
            return DataTables::of($companies)
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('companies.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                            <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|unique:companies,email_address',
            'solicitor_name' => 'nullable|string|max:255',
            'regulated_by' => 'nullable|in:Law Society,Immigration Advice Authority',
            'company_reg_number' => 'required|string|unique:companies,company_reg_number',
            'logo' => 'nullable|image|max:2048',
            'accreditor_logos.*' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            $paths = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $paths[] = $logo->store('accreditor_logos', 'public');
            }
            $validated['accreditor_logos'] = json_encode($paths);
        }

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|unique:companies,email_address,' . $company->id,
            'solicitor_name' => 'nullable|string|max:255',
            'regulated_by' => 'nullable|in:Law Society,Immigration Advice Authority',
            'company_reg_number' => 'required|string|unique:companies,company_reg_number,' . $company->id,
            'logo' => 'nullable|image|max:2048',
            'accreditor_logos.*' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            if ($company->accreditor_logos) {
                foreach (json_decode($company->accreditor_logos, true) as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $paths = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $paths[] = $logo->store('accreditor_logos', 'public');
            }
            $validated['accreditor_logos'] = json_encode($paths);
        }

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }
        if ($company->accreditor_logos) {
            foreach (json_decode($company->accreditor_logos, true) as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $company->delete();

        return response()->json(['success' => 'Company deleted successfully.']);
    }
}