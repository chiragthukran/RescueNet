<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = User::where('status', 'active')
            ->withCount('resources')
            ->latest()
            ->paginate(12);

        return view('agencies.index', compact('agencies'));
    }

    public function show(User $user)
    {
        $user->load('resources');
        return view('agencies.show', compact('user'));
    }
}
