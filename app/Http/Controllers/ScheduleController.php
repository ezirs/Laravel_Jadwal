<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('status', 'accepted')->get();
        return view('schedules.index', compact('schedules'));
    }
    
    public function indexAdmin()
    {
        $schedules = Schedule::where('status', 'accepted')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'title' => 'required|string|max:255',
            'use_datetime' => 'required|boolean',
            'start' => 'required_if:use_datetime,false|date|nullable',
            'start_datetime' => 'required_if:use_datetime,true|date_format:Y-m-d\TH:i:s|nullable',
            'end' => 'date|after_or_equal:start|nullable',
            'end_datetime' => 'date_format:Y-m-d\TH:i:s|after_or_equal:start_datetime|nullable',
            'link' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'schedule_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/|max:255',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'use_datetime' => $request->use_datetime,
            'start' => $request->start,
            'start_datetime' => $request->start_datetime,
            'end' => $request->end,
            'end_datetime' => $request->end_datetime,
            'schedule_color' => $request->schedule_color,
            'link' => $request->link,
            'description' => $request->description,
            'status' => auth()->user()->role == 'admin' ? 'accepted' : 'pending',
        ];

        if ($request->id) {
            Schedule::where('id', $request->id)->update($data);
            return response()->json(['message' => 'Jadwal berhasi diubah']);
        } else {
            Schedule::create($data);
            return response()->json(['message' => 'Jadwal berhasi dibuat']);
        }

    }

    public function update(Request $request, $scheduleId)
    {
        $request->validate([
            'use_datetime' => 'required|boolean',
            'start' => 'required_if:use_datetime,false|date|nullable',
            'start_datetime' => 'required_if:use_datetime,true|date_format:Y-m-d\TH:i:s|nullable',
            'end' => 'date|after_or_equal:start|nullable',
            'end_datetime' => 'date_format:Y-m-d\TH:i:s|after_or_equal:start_datetime|nullable'
        ]);

        Schedule::where('id', $scheduleId)->update([
            'start' => $request->start,
            'start_datetime' => $request->start_datetime,
            'end' => $request->end,
            'end_datetime' => $request->end_datetime
        ]);

        return response()->json(['message' => 'Jadwal berhasil diperbarui']);
    }

    public function destroy($scheduleId)
    {
        Schedule::where('id', $scheduleId)->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
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
