<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAttendance;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AttendanceController extends Controller
{
    public function getTodayAttendance()
    {
        // Get the current user's attendance record for today
        // Assuming you have a relationship set up in your User model
        // to get the attendance records
        $user = auth()->user();
        $todayAttendance = UserAttendance::where('user_id', $user->id)
            ->whereDate('check_in_date', today())
            ->latest()
            ->first();
        return $todayAttendance;
    }

    public function getCurrentAttendance()
    {
        // Get the current user's attendance record for today
        // Assuming you have a relationship set up in your User model
        // to get the attendance records
        $user = auth()->user();
        $currentAttendance = UserAttendance::where('user_id', $user->id)
            ->whereNull('check_out_date', )
            ->latest()
            ->first();
        return $currentAttendance;
    }


    public function index()
    {
        $user = auth()->user();

        // Get the most recent attendance record without check-out
        $currentAttendance = UserAttendance::where('user_id', $user->id)
            ->whereNull('check_out_time')
            ->latest()
            ->first();

        // Get attendance history (you can paginate if needed)
        $attendances = UserAttendance::where('user_id', $user->id)
            ->orderByDesc('check_in_date')
            ->orderByDesc('check_in_time')
            ->with('approver.profile') // preload related approver/profile
            ->get();

        return view('attendances.index', compact('attendances', 'currentAttendance'));
    }

    public function show($id)
    {
        $user = auth()->user();

    $attendance = UserAttendance::where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail(); // <-- This line is essential

    return view('attendances.detail-card', compact('attendance'));
    }



    public function pending()
    {
        $user = auth()->user();

        // Optional: restrict to managers/admins only
        if (!in_array($user->user_level, ['manager', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // Get all pending attendances
        $attendances = UserAttendance::with('user.profile') // preload related user/profile
            ->where('approval_status', 'pending')
            ->orderByDesc('check_in_date')
            ->orderByDesc('check_in_time')
            ->get();

        return view('main.attendance-pending', compact('attendances'));
    }

    /**
     * Check in the employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkIn(Request $request)
    {
        // Ensure only one check-in is allowed for a user per day
        $attendance = UserAttendance::where('user_id', auth()->id())
            ->whereNull('check_out_date')  // Make sure the user hasn't checked out yet
            ->latest('check_in_date')
            ->first();

        if ($attendance === null) {
            // Create new check-in record
            $attendance = new UserAttendance();
            $attendance->user_id = auth()->id();
            $attendance->check_in_date = now()->toDateString(); // Current date
            $attendance->check_in_time = now()->toTimeString(); // Current time
            // Get client IP address
            $attendance->check_in_ip = $request->ip();
            $attendance->check_in_latitude = $request->latitude; // Assuming you have latitude in the request
            $attendance->check_in_longitude = $request->longitude; // Assuming you have longitude in the request
            $attendance->work_mode = $request->work_mode; // Assuming you have work mode in the request

            if ($request->hasFile('selfie')) {
                // Generate a unique filename for the selfie
                $userid = str_pad(auth()->user()->id, 6, '0', STR_PAD_LEFT);
                $prefix = config('custom.prefix.img.check.in');
                $timestamp = now()->format('YmdHis'); // e.g. 20250513142532
                //$filename = "img_checkin%-%-{$timestamp}%-%-{$username}.png";
                $filename = "{$prefix}{$userid}{$timestamp}.png";

                // Convert to PNG and resize to width 512px (height auto, keeps aspect ratio)
                $image = $request->file('image');

                // Create an image manager
                $manager = new ImageManager(new Driver());

                $image = $manager->read($request->file('selfie')->getPathname())
                    ->scale(height: 300)
                    ->toPng();

                //uncomment below if you want to save the image in storage
                // Save to storage/app/public/img
                Storage::disk('public')->put("img/{$filename}", $image);

                // this will save the image in public/img
                /*
                $publicPath = public_path("img");

                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                $image->save("{$publicPath}/{$filename}");
                */
                $attendance->check_in_img_path = "img/{$filename}";

            }

            $attendance->save();
        }


        return back()->with('status', 'Checked in successfully!');
    }

    /**
     * Check out the employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkOut(Request $request)
    {
        // Ensure user has checked in before attempting to check out
        $attendance = UserAttendance::where('user_id', auth()->id())
            ->whereNull('check_out_date')  // Find the latest check-in without a check-out
            ->latest('check_in_date')
            ->first();

        if ($attendance) {


            $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
            $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
            $durationInSeconds = $checkIn->diffInSeconds($checkOut);

            $attendance->work_duration = $durationInSeconds;
            $attendance->check_out_date = now()->toDateString(); // Current date
            $attendance->check_out_time = now()->toTimeString(); // Current time
            // Get client IP address
            $attendance->check_out_ip = $request->ip();
            $attendance->check_out_latitude = $request->latitude; // Assuming you have latitude in the request
            $attendance->check_out_longitude = $request->longitude; // Assuming you have longitude in the request

            if ($request->hasFile('selfie')) {
                // Generate a unique filename for the selfie
                $userid = str_pad(auth()->user()->id, 6, '0', STR_PAD_LEFT);
                $prefix = config('custom.prefix.img.check.out');
                $timestamp = now()->format('YmdHis'); // e.g. 20250513142532
                //$filename = "img_checkout%-%-{$timestamp}%-%-{$username}.png";
                $filename = "{$prefix}{$userid}{$timestamp}.png";

                // Convert to PNG and resize to width 512px (height auto, keeps aspect ratio)
                $image = $request->file('image');

                // Create an image manager
                $manager = new ImageManager(new Driver());

                $image = $manager->read($request->file('selfie')->getPathname())
                    ->scale(height: 300)
                    ->toPng();

                //uncomment below if you want to save the image in storage
                // Save to storage/app/public/img
                Storage::disk('public')->put("img/{$filename}", $image);

                // this will save the image in public/img
                /*
                $publicPath = public_path("img");

                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                $image->save("{$publicPath}/{$filename}");
                */
                $attendance->check_out_img_path = "img/{$filename}";

            }
            $attendance->save();
        }

        return back()->with('status', 'Checked in successfully!');
    }

    public function approve($id)
    {
        $attendance = UserAttendance::findOrFail($id);

        // Change the approval status to 'approved'
        $attendance->approval_status = 'approved';
        $attendance->approved_by = auth()->user()->id; // Assuming you want to set the approver
        $attendance->approved_at = now(); // Set the approval timestamp
        $attendance->save();

        return redirect()->route('attendance.pending')->with('success', 'Attendance approved.');
    }

    public function reject($id)
    {
        $attendance = UserAttendance::findOrFail($id);

        // Change the approval status to 'rejected'
        $attendance->approval_status = 'rejected';
        $attendance->approved_by = auth()->user()->id; // Assuming you want to set the approver
        $attendance->approved_at = now(); // Set the approval timestamp
        $attendance->save();

        return redirect()->route('attendance.pending')->with('success', 'Attendance rejected.');
    }

}

