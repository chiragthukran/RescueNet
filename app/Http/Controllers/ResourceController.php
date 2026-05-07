<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $resources = $request->user()->resources()->latest()->get();
        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:personnel,vehicle,equipment,medical,supplies',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'required|integer|min:0|lte:quantity',
            'status' => 'required|in:available,deployed,maintenance',
            'notes' => 'nullable|string|max:500',
        ]);

        $request->user()->resources()->create($validated);

        return redirect()->route('resources.index')->with('success', 'Resource added successfully.');
    }

    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:personnel,vehicle,equipment,medical,supplies',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'required|integer|min:0|lte:quantity',
            'status' => 'required|in:available,deployed,maintenance',
            'notes' => 'nullable|string|max:500',
        ]);

        $resource->update($validated);

        return redirect()->route('resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);
        $resource->delete();

        return redirect()->route('resources.index')->with('success', 'Resource deleted successfully.');
    }
}
