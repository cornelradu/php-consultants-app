<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function showAllConsultants(Request $request)
    {
        return view('consultants_list');
    }

    public function register(Request $request)
    {
        return view('register');
    }

    public function loginform(Request $request)
    {
        return view('loginform');
    }
}
