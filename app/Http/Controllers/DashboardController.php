<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Calamity;
use App\Models\Message;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_agencies' => User::where('status', 'active')->count(),
            'active_calamities' => Calamity::where('status', 'active')->count(),
            'total_resources' => Resource::sum('quantity'),
            'available_resources' => Resource::sum('available_quantity'),
            'my_resources' => $user->resources()->count(),
            'my_alerts' => Alert::where(function ($q) use ($user) {
                $q->where('target_user_id', $user->id)->orWhere('is_broadcast', true);
            })->whereNull('acknowledged_at')->count(),
            'unread_messages' => Message::where('receiver_id', $user->id)->whereNull('read_at')->count(),
            'critical_calamities' => Calamity::where('status', 'active')->where('severity', 'critical')->count(),
        ];

        $recentAlerts = Alert::where(function ($q) use ($user) {
            $q->where('target_user_id', $user->id)->orWhere('is_broadcast', true);
        })->with('calamity', 'creator')->latest()->take(5)->get();

        $activeCalamities = Calamity::where('status', 'active')
            ->with('reporter')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentAlerts', 'activeCalamities'));
    }
}
