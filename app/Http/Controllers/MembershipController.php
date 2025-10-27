<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        return view('memberships.index', [
        ]);
    }

    public function verify(Request $request)
    {
        if ($request->post('membership_number')) {
            $user = Membership::matchMember($request->post('membership_number'));
        }
        return view('memberships.verify', [
            'user' => $user ?? null,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('manage memberships')) {
            return redirect(route('dashboard'));
        }
        return view('memberships.edit', [
            'membership' => new Membership(),
        ]);
    }

    public function edit(Request $request, $membershipId)
    {
        if ($request->user()->cannot('manage memberships')) {
            return redirect(route('dashboard'));
        }
        return view('memberships.edit', [
            'membership' => Membership::findOrFail($membershipId),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('manage memberships')) {
            return redirect(route('dashboard'));
        }
        $validatedData = $request->validate([
            'id' => 'sometimes|exists:events,id',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        if (!empty($validatedData['id'])) {
            $event = Membership::findOrFail($validatedData['id']);
        } else {
            $event = new Membership();
        }
        if ($validatedData['start_date'] > $validatedData['end_date']) {
            return redirect()->back()->withErrors(['start_date' => 'Start date must be before end date.']);
        }
        $event->fill($validatedData);
        $event->save();
        return redirect(route('memberships.edit', $event->id));
    }
}
