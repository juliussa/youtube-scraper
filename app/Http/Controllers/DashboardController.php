<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{

    /**
     * Display dashboard
     */
    public function index()
    {
        return view('dashboard');
    }
}
