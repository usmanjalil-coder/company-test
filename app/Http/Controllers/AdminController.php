<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $companies = Company::select('id', 'company_name', 'company_address', 'contact_number', 'email_address', 'solicitor_name', 'regulated_by', 'company_reg_number');
            return DataTables::of($companies)
                ->addColumn('actions', function($row) {
                    return '<a href="' . route('company.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                            <a href="' . route('company.delete', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('company.index');
    }
    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email',
            'solicitor_name' => 'required|string|max:255',
            'regulated_by' => 'required|in:Law Society,Immigration Advice Authority',
            'company_reg_number' => 'required|string|max:20',
            'company_logo' => 'nullable|image|max:2048',
            'accreditor_logos' => 'nullable|array',
            'accreditor_logos.*' => 'image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            $logos = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $logos[] = $logo->store('logos', 'public');
            }
            $data['accreditor_logos'] = json_encode($logos);
        }

        Company::create($data);

        return redirect()->back()->with('success', 'Company information saved successfully.');
    }
     public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email',
            'solicitor_name' => 'required|string|max:255',
            'regulated_by' => 'required|in:Law Society,Immigration Advice Authority',
            'company_reg_number' => 'required|string|max:20',
            'company_logo' => 'nullable|image|max:2048',
            'accreditor_logos' => 'nullable|array',
            'accreditor_logos.*' => 'image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($company->company_logo) {
                \Storage::disk('public')->delete($company->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            // Delete old logos if exists
            if ($company->accreditor_logos) {
                $oldLogos = json_decode($company->accreditor_logos, true);
                foreach ($oldLogos as $oldLogo) {
                    \Storage::disk('public')->delete($oldLogo);
                }
            }
            $logos = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $logos[] = $logo->store('logos', 'public');
            }
            $data['accreditor_logos'] = json_encode($logos);
        }

        $company->update($data);

        return redirect()->route('company.index')->with('success', 'Company information updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->company_logo) {
            \Storage::disk('public')->delete($company->company_logo);
        }
        if ($company->accreditor_logos) {
            $oldLogos = json_decode($company->accreditor_logos, true);
            foreach ($oldLogos as $oldLogo) {
                \Storage::disk('public')->delete($oldLogo);
            }
        }

        $company->delete();

        return response()->json(['success' => 'Company deleted successfully.']);
    }
}