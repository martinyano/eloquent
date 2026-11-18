<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EloquentRelationshipController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        // REQUIREMENT 3: Guard the dashboard/relationships page
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        return view('eloquent-relationships', [
            'users' => User::orderBy('name')->get(),
            'oneToOneUsers' => User::with('house')->whereHas('house')->orderBy('name')->get(),
            'oneToManyUsers' => User::with('houses')->whereHas('houses')->orderBy('name')->get(),
            'manyToManyUsers' => User::with('groups')->whereHas('groups')->orderBy('name')->get(),
            'groups' => Group::orderBy('name')->get(),
        ]);
    }

    public function storeOneToOne(Request $request): RedirectResponse
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'oto_name' => ['required', 'string', 'max:255'],
            'oto_address' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create(['name' => $data['oto_name']]);
            $user->house()->create(['address' => $data['oto_address']]);
        });

        return back()->with('status', 'User and house added.');
    }

    public function storeOneToMany(Request $request): RedirectResponse
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'otm_name' => ['required', 'string', 'max:255'],
            'otm_address' => ['required', 'string', 'max:255'],
        ]);

        // Clean up input spaces
        $userName = trim($data['otm_name']);

        // Find existing user case-insensitively, or create if they truly don't exist
        $user = User::where('name', 'LIKE', $userName)->first();

        if (!$user) {
            $user = User::create(['name' => $userName]);
        }

        // Attach the house to the correct user
        $user->houses()->create(['address' => $data['otm_address']]);

        return back()->with('status', 'House added.');
    }

    public function storeManyToMany(Request $request): RedirectResponse
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'mtm_name' => ['required', 'string', 'max:255'],
            'mtm_groups' => ['required', 'array', 'min:1'],
            'mtm_groups.*' => ['string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $userName = trim($data['mtm_name']);
            
            $user = User::where('name', 'LIKE', $userName)->first();
            if (!$user) {
                $user = User::create(['name' => $userName]);
            }

            $groupIds = collect($data['mtm_groups'])
                ->map(fn (string $name) => Group::firstOrCreate(['name' => trim($name)])->id);

            $user->groups()->syncWithoutDetaching($groupIds);
        });

        return back()->with('status', 'User added to groups.');
    }
}