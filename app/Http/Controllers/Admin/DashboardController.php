<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Service;
use App\Models\Payment;
use App\Models\WhmServer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $today = Carbon::today();

    return view('admin.dashboard', [
        'todayNewUsers'     => User::whereDate('created_at', $today)->count(),
        'todayNewServers'   => Service::whereDate('created_at', $today)->count(),
        'todaySales'        => Payment::whereDate('created_at', $today)->sum('amount'),
        'totalUsers'        => User::count(),
        'totalServers'      => Service::count(),
        'totalWhmServers'   => WhmServer::count(),
    ]);
}
}
