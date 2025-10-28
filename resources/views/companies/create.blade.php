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
                <h5 class="card-title">Company Information Form</h5>
                <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Name of Company:</label>
                        <input type="text" class="form-control" id="companyName" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="companyAddress" class="form-label">Address of Company:</label>
                        <textarea class="form-control" id="companyAddress" name="company_address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number:</label>
                        <input type="tel" class="form-control" id="contactNumber" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address:</label>
                        <input type="email" class="form-control" id="emailAddress" name="email_address" required>
                    </div>
                    <div class="mb-3">
                        <label for="solicitorName" class="form-label">Name of Solicitor or Advisor:</label>
                        <input type="text" class="form-control" id="solicitorName" name="solicitor_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regulated by:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regulated_by" id="lawSociety"
                                value="Law Society" required>
                            <label class="form-check-label" for="lawSociety">The Law Society</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regulated_by" id="immigrationAdvice"
                                value="Immigration Advice Authority">
                            <label class="form-check-label" for="immigrationAdvice">Immigration Advice Authority</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="companyRegNumber" class="form-label">Company registration number:</label>
                        <input type="text" class="form-control" id="companyRegNumber" name="company_reg_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="companyLogo" class="form-label">Company Logo:</label>
                        <input type="file" class="form-control" id="companyLogo" name="company_logo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="accreditorLogos" class="form-label">Accreditor/Other logos:</label>
                        <input type="file" class="form-control" id="accreditorLogos" name="accreditor_logos[]"
                            accept="image/*" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
