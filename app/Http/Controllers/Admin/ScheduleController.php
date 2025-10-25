<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('times')->latest()->paginate(10);
        return view('dashboard.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date|unique:schedules,date',
            'is_available' => 'nullable',
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
        ]);

        $schedule = Schedule::create([
            'date' => $data['date'],
            'is_available' => $request->has('is_available'),
        ]);

        $schedule->times()->createMany(
            collect($data['times'])->map(fn($t) => ['time' => $t])->toArray()
        );

        return redirect()->route('dashboard.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $data = $request->validate([
            'date' => 'required|date',
            'is_available' => 'nullable',
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
        ]);

        $schedule->update([
            'date' => $data['date'],
            'is_available' => $request->has('is_available'),
        ]);

        $schedule->times()->delete();
        $schedule->times()->createMany(
            collect($data['times'])->map(fn($t) => ['time' => $t])->toArray()
        );

        return redirect()->route('dashboard.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->times()->delete();
        $schedule->delete();

        return redirect()->route('dashboard.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
