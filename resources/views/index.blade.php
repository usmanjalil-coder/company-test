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
                <h5 class="card-title">Authority Letter</h5>
                <p class="card-text">Content for Authority Letter goes here.</p>
            </div>
        </div>
    </div>
@endsection
