<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display Dashboard
     */
    public function index()
    {
        return view('dashboard', [
            'user' => auth()->user(),
        ]);
    }
}
