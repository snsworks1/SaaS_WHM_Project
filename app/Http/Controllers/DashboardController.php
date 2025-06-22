<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Notice;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $services = $user->services;
        $notices = Notice::latest()->take(3)->get();

        $activeServiceCount = $services->count();

        $expiringSoonCount = $services->filter(function ($service) {
            return $service->expires_at && now()->diffInDays($service->expires_at, false) <= 3;
        })->count();

        $monthlyTotal = $services->sum(function ($service) {
            return $service->plan->price ?? 0;
        });

        return view('dashboard', compact(
            'services',
            'activeServiceCount',
            'expiringSoonCount',
            'monthlyTotal',
            'notices'
        ));
    }
}
