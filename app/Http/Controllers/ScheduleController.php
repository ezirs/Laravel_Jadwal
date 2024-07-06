<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('status', 'accepted')->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'status' => 'pending',
        ]);

        return redirect()->route('schedules.index');
    }

    public function accept(Schedule $schedule)
    {
        $schedule->update(['status' => 'accepted']);
        return redirect()->back();
    }

    public function reject(Schedule $schedule)
    {
        $schedule->update(['status' => 'rejected']);
        return redirect()->back();
    }
}
