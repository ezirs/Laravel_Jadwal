<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('status', 'accepted')->get();
        return ScheduleResource::collection($schedules)->toArray(request());
    }
}
