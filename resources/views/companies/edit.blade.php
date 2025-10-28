@extends('layouts.app')
@section('content')
    <div class="content">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Admin Panel</span>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Edit Company</h5>
                <form action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Name of Company:</label>
                        <input type="text" class="form-control" id="companyName" name="company_name"
                            value="{{ old('company_name', $company->company_name) }}" required>
                        @error('company_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="companyAddress" class="form-label">Address of Company:</label>
                        <textarea class="form-control" id="companyAddress" name="company_address" rows="3" required>{{ old('company_address', $company->company_address) }}</textarea>
                        @error('company_address')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number:</label>
                        <input type="tel" class="form-control" id="contactNumber" name="contact_number"
                            value="{{ old('contact_number', $company->contact_number) }}" required>
                        @error('contact_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address:</label>
                        <input type="email" class="form-control" id="emailAddress" name="email_address"
                            value="{{ old('email_address', $company->email_address) }}" required>
                        @error('email_address')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="solicitorName" class="form-label">Name of Solicitor or Advisor:</label>
                        <input type="text" class="form-control" id="solicitorName" name="solicitor_name"
                            value="{{ old('solicitor_name', $company->solicitor_name) }}" required>
                        @error('solicitor_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regulated by:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regulated_by" id="lawSociety"
                                value="Law Society"
                                {{ old('regulated_by', $company->regulated_by) == 'Law Society' ? 'checked' : '' }}
                                required>
                            <label class="form-check-label" for="lawSociety">The Law Society</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regulated_by" id="immigrationAdvice"
                                value="Immigration Advice Authority"
                                {{ old('regulated_by', $company->regulated_by) == 'Immigration Advice Authority' ? 'checked' : '' }}>
                            <label class="form-check-label" for="immigrationAdvice">Immigration Advice Authority</label>
                        </div>
                        @error('regulated_by')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="companyRegNumber" class="form-label">Company registration number:</label>
                        <input type="text" class="form-control" id="companyRegNumber" name="company_reg_number"
                            value="{{ old('company_reg_number', $company->company_reg_number) }}" required>
                        @error('company_reg_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="companyLogo" class="form-label">Company Logo:</label>
                        <input type="file" class="form-control" id="companyLogo" name="company_logo" accept="image/*">
                        @if ($company->company_logo)
                            <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Current Logo"
                                style="max-width: 200px; margin-top: 10px;">
                        @endif
                        @error('company_logo')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="accreditorLogos" class="form-label">Accreditor/Other logos:</label>
                        <input type="file" class="form-control" id="accreditorLogos" name="accreditor_logos[]"
                            accept="image/*" multiple>
                        @if ($company->accreditor_logos)
                            @foreach (json_decode($company->accreditor_logos, true) as $logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Current Logo"
                                    style="max-width: 200px; margin-top: 10px;">
                            @endforeach
                        @endif
                        @error('accreditor_logos')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('company.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
