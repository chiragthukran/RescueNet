<?php

namespace App\Http\Controllers;

use App\Models\Calamity;
use App\Models\LocationLog;
use App\Models\User;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function data(Request $request)
    {
        $agencies = User::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'name', 'organization', 'agency_type', 'phone', 'latitude', 'longitude')
            ->withCount('resources')
            ->get();

        $calamities = Calamity::where('status', '!=', 'resolved')
            ->select('id', 'title', 'type', 'custom_type', 'severity', 'latitude', 'longitude', 'radius_km', 'status', 'created_at')
            ->get()
            ->map(function ($c) {
                $c->display_type = $c->display_type;
                return $c;
            });

        return response()->json([
            'agencies' => $agencies,
            'calamities' => $calamities,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = $request->user();
        $user->update($validated);

        LocationLog::create([
            'user_id' => $user->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json(['success' => true]);
    }
}
