<?php

namespace App\Services\Dashboard\Leader;

use App\Models\Screening;
use Illuminate\Support\Facades\Auth;

class ScreeningsDashboardServices
{
    public function getTotalNumberOfScreenings($confirmationStatus)
    {
        return Screening::where('confirmation_status', $confirmationStatus)->where('leader_id', Auth::user()->leader->id)->count();
    }
    public function getLatestScreenings()
    {
        return Screening::where('leader_id', Auth::user()->leader->id)->latest()->limit(5);
    }

    public function getTotalNumberOfScreeningsPerMonth($month)
    {
        return Screening::whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->where('leader_id', Auth::user()->leader->id)->count();
    }
}
