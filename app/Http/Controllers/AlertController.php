<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Calamity;
use App\Models\User;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $alerts = Alert::where(function ($q) use ($user) {
            $q->where('target_user_id', $user->id)
              ->orWhere('is_broadcast', true)
              ->orWhere('created_by', $user->id);
        })
        ->with('calamity', 'creator', 'targetUser')
        ->latest()
        ->paginate(15);

        return view('alerts.index', compact('alerts'));
    }

    public function create()
    {
        $calamities = Calamity::where('status', 'active')->get();
        $users = User::where('status', 'active')->get();
        return view('alerts.create', compact('calamities', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,critical',
            'calamity_id' => 'nullable|exists:calamities,id',
            'target_user_id' => 'nullable|exists:users,id',
            'is_broadcast' => 'boolean',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['is_broadcast'] = $request->boolean('is_broadcast');

        if ($validated['is_broadcast']) {
            $validated['target_user_id'] = null;
        }

        Alert::create($validated);

        return redirect()->route('alerts.index')->with('success', 'Alert sent successfully.');
    }

    public function acknowledge(Request $request, Alert $alert)
    {
        $alert->update(['acknowledged_at' => now()]);
        return redirect()->back()->with('success', 'Alert acknowledged.');
    }
}
