<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InvestorDashboardController extends Controller
{
    public function index(): View
    {
        return view('portal.investor.dashboard');
    }
}
