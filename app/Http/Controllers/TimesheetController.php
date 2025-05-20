<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timesheets = auth()->user()->userTimesheets()
            ->with('details')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        return view('timesheets.index', compact('timesheets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form for creating a new timesheet
        return view('timesheets.create1');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'date' => 'required|date',
            'total_time' => 'required|string|regex:/^\d+h\s\d+m$/',
            'entries' => 'required|array|min:1',
            'entries.*.time_start' => 'required|date_format:H:i',
            'entries.*.time_end' => 'required|date_format:H:i|after:entries.*.time_start',
            'entries.*.total_time' => 'required|string|regex:/^\d+h\s\d+m$/',
            'entries.*.job_code' => 'required|string|max:50',
            'entries.*.job_descriptions' => 'required|string|max:1000',
            'entries.*.remark' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Calculate total time and number of work entries
                $totalMinutes = 0;
                $numberOfWork = count($validated['entries']);

                foreach ($validated['entries'] as $entry) {
                    // Parse time entries
                    [$startHour, $startMinute] = explode(':', $entry['time_start']);
                    [$endHour, $endMinute] = explode(':', $entry['time_end']);

                    $startTotal = (int) $startHour * 60 + (int) $startMinute;
                    $endTotal = (int) $endHour * 60 + (int) $endMinute;

                    // Handle overnight shifts
                    if ($endTotal < $startTotal) {
                        $endTotal += 1440; // Add 24 hours in minutes
                    }

                    $totalMinutes += ($endTotal - $startTotal);
                }

                // Verify calculated total matches submitted total
                $calculatedTotal = floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';
                if ($calculatedTotal !== $validated['total_time']) {
                    throw new \Exception('Submitted total time does not match calculated time');
                }

                // Save Timesheet
                $timesheet = Timesheet::create([
                    'user_id' => auth()->id(),
                    'date' => $validated['date'],
                    'total_time' => $validated['total_time'],
                    'number_of_work' => $numberOfWork,
                    'review_status' => 'pending',
                    'created_at' => now(),
                    'edited_at' => now(),
                ]);

                // Save Timesheet Details
                foreach ($validated['entries'] as $entry) {
                    // Parse time for this entry
                    [$startHour, $startMinute] = explode(':', $entry['time_start']);
                    [$endHour, $endMinute] = explode(':', $entry['time_end']);

                    $startTotal = (int) $startHour * 60 + (int) $startMinute;
                    $endTotal = (int) $endHour * 60 + (int) $endMinute;

                    if ($endTotal < $startTotal) {
                        $endTotal += 1440;
                    }

                    $diffMinutes = $endTotal - $startTotal;
                    $entryTotalTime = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';

                    // Verify entry total matches calculated total
                    if ($entryTotalTime !== $entry['total_time']) {
                        throw new \Exception('Entry total time mismatch for job code: ' . $entry['job_code']);
                    }

                    TimesheetDetail::create([
                        'timesheet_id' => $timesheet->id,
                        'time_start' => $entry['time_start'],
                        'time_end' => $entry['time_end'],
                        'total_time' => $entry['total_time'],
                        'job_code' => $entry['job_code'],
                        'job_descriptions' => $entry['job_descriptions'],
                        'remark' => $entry['remark'] ?? null,
                        'created_at' => now(),
                        'edited_at' => now(),
                    ]);
                }
            });

            return redirect()->route('timesheets.index')
                ->with('success', 'Timesheet saved successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error saving timesheet: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet)
    {
        // Show the details of a specific timesheet
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }
}
