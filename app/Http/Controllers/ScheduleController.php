<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // GET /api/schedules => عرض كل المواعيد
    public function index()
    {
        $schedules = Schedule::with('times')->get()->map(function ($schedule) {
            return [
                'date' => Carbon::parse($schedule->date)->toISOString(),
                'isAvailable' => $schedule->is_available,
                'times' => $schedule->times->map(function ($time) use ($schedule) {
                    $dateTime = Carbon::parse($schedule->date)
                        ->setTimeFromTimeString($time->time);
                    return [
                        'id' => $time->id,
                        'time' => $dateTime->toISOString(),
                    ];
                })->values(),
            ];
        });

        return response()->json($schedules);
    }

    // POST /api/schedules => إضافة يوم ومواعيد
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'is_available' => 'boolean',
            'times' => 'required|array',
            'times.*' => 'date_format:H:i'
        ]);

        $schedule = Schedule::create([
            'date' => $data['date'],
            'is_available' => $data['is_available'] ?? true,
        ]);

        foreach ($data['times'] as $time) {
            ScheduleTime::create([
                'schedule_id' => $schedule->id,
                'time' => $time,
            ]);
        }

        return response()->json(['message' => 'Schedule created successfully']);
    }
}
