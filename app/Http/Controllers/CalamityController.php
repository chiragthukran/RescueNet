<?php

namespace App\Http\Controllers;

use App\Models\Calamity;
use Illuminate\Http\Request;

class CalamityController extends Controller
{
    public function index()
    {
        $calamities = Calamity::with('reporter')
            ->latest()
            ->paginate(12);

        return view('calamities.index', compact('calamities'));
    }

    public function create()
    {
        return view('calamities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:earthquake,flood,fire,cyclone,tsunami,landslide,industrial,other',
            'custom_type' => 'nullable|required_if:type,other|string|max:255',
            'description' => 'nullable|string|max:2000',
            'severity' => 'required|in:low,medium,high,critical',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'required|numeric|min:0.1|max:500',
        ]);

        $validated['reported_by'] = $request->user()->id;
        $validated['status'] = 'active';

        Calamity::create($validated);

        return redirect()->route('calamities.index')->with('success', 'Calamity reported successfully.');
    }

    public function show(Calamity $calamity)
    {
        $calamity->load('reporter', 'alerts.creator', 'messages.sender');
        return view('calamities.show', compact('calamity'));
    }

    public function update(Request $request, Calamity $calamity)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,contained,resolved',
            'severity' => 'sometimes|in:low,medium,high,critical',
        ]);

        $calamity->update($validated);

        return redirect()->route('calamities.show', $calamity)->with('success', 'Calamity updated.');
    }
}
