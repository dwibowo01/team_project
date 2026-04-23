<?php

namespace App\Http\Controllers;

use App\Enums\TeamName;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirect the authenticated user to their active team dashboard.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $teams = $user->allTeams();

        // If user is in the Management team, redirect to management dashboard
        $managementTeam = $teams->first(fn ($t) => $t->type === TeamName::Management);
        if ($managementTeam) {
            return redirect()->route('management.dashboard');
        }

        // If user belongs to exactly one team, redirect to it
        if ($teams->count() === 1) {
            return redirect()->route($teams->first()->dashboardRoute());
        }

        // Show a team selection screen
        return view('dashboard', compact('teams'));
    }
}
