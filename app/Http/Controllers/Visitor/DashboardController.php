<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('visitor.dashboard');
    }
}