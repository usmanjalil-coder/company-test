<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            // include company_logo and accreditor_logos in select so they are available server-side
            $companies = Company::select(
                'id',
                'company_name',
                'company_address',
                'contact_number',
                'email_address',
                'solicitor_name',
                'regulated_by',
                'company_reg_number',
                'company_logo',
                'accreditor_logos'
            );

            return DataTables::of($companies)
                // NEW: company logo column (returns HTML img 60px)
                ->addColumn('company_logo', function($row) {
                    if (!empty($row->company_logo)) {
                        $url = Storage::url($row->company_logo);
                    } else {
                        $url = asset('images/default-company.png');
                    }
                    return '<img src="' . e($url) . '" width="60" style="object-fit:cover"/>';
                })

                // NEW: accreditor logos column (may contain multiple images)
                ->addColumn('accreditor_logos', function($row) {
                    $html = '';
                    $logos = json_decode($row->accreditor_logos, true) ?: [];
                    foreach ($logos as $logo) {
                        if (!$logo) continue;
                        $url = Storage::url($logo);
                        $html .= '<img src="' . e($url) . '" width="60" style="object-fit:cover;margin-right:4px"/>';
                    }
                    return $html;
                })

                ->addColumn('actions', function($row) {
                    return '<a href="' . route('company.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                            <a href="' . route('company.delete', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</a>';
                })
                // allow HTML for our image columns + actions
                ->rawColumns(['company_logo', 'accreditor_logos', 'actions'])
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
            // delete old logo if exists
            if ($company->company_logo) {
                Storage::disk('public')->delete($company->company_logo);
                Storage::disk('public')->delete($company->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            // Delete old logos if exists
            if ($company->accreditor_logos) {
                $oldLogos = json_decode($company->accreditor_logos, true);
                foreach ($oldLogos as $oldLogo) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }
            $logos = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $existing[] = $logo->store('logos', 'public');
            }
            $data['accreditor_logos'] = json_encode($existing);
        } else {
            // keep existing if no new upload
            $data['accreditor_logos'] = $company->accreditor_logos;
        }

        $company->update($data);

        return redirect()->back()->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->company_logo) {
            Storage::disk('public')->delete($company->company_logo);
        }
        if ($company->accreditor_logos) {
            $oldLogos = json_decode($company->accreditor_logos, true);
            foreach ($oldLogos as $oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        $company->delete();
        return response()->json(['success' => 'Company deleted.']);
    }
}