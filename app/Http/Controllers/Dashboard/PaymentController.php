<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('plan')
            ->where('user_id', Auth::id())
            ->orderByDesc('approved_at')
            ->get();

        return view('dashboard.payments', compact('payments'));
    }
}
