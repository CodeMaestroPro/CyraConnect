<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CorporateDashboardController extends Controller
{
    public function index(): View
    {
        return view('portal.corporate.dashboard');
    }
}
