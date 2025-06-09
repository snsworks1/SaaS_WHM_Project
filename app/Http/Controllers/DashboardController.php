<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $services = auth()->user()->services()->with('plan')->latest()->get();

        return view('dashboard', compact('services'));
    }
}
