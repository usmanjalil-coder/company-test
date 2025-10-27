<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthorityLetter;
use Yajra\DataTables\Facades\DataTables;

class AuthorityLetterController extends Controller
{
    public function index() {
    if (request()->ajax()) {
        $letters = AuthorityLetter::select('id', 'client_id', 'content', 'date');
        return DataTables::of($letters)->make(true);
    }
    return view('authority-letters.index');
}
}
