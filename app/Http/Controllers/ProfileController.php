<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;




class ProfileController extends Controller
{




    public function show(User $user = null)
    {
        // If no user is provided, use the authenticated user
        // Check if the user is an admin
        $currentUser = auth()->user();

        if ($user === null || $currentUser->user_level == 'staff') {
            // Show own profile if:
            // - No user was provided
            // - Or current user is not admin
            return view('profile.show', ['user' => $currentUser]);
        }


        if (request()->ajax()) {
            // If request is AJAX (e.g. modal)
            // Check if the user is an admin
            return view('profile.show-card', compact('user'));
        }

        // Full page view
        return view('profile.show', compact('user'));
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->user_level == 'staff') {
            return view('profile.show', compact('user'));
        }

        $users = User::where('user_level', '!=', 'admin')
            ->orderBy('username')
            ->get();
        return view('profile.index', compact('users'));

    }


    public function edit(User $user = null)
    {
        $currentUser = auth()->user();
        if ($currentUser->isAdmin() || $user !== null) {
            // Check if the user is an admin
            $userProfile = $user->profile;

            return view('profile.edit', compact('user', 'userProfile'));
        }

        // Non-admin or no user provided: edit own profile
        $user = $currentUser;
        $userProfile = $user->profile;

        // If the user is not an admin, redirect to the edit page of their own profile
        return view('profile.edit', compact('user', 'userProfile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:100',
            'division' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            // 'entry_date' is managed by HR, so no update
        ]);

        // Check if the user is an admin
        if (!auth()->user()->isAdmin()) {
            $profile = $request->user()->profile;
        }
        $profile = auth()->user()->profile;

        $profile->update([
            'fullname' => $request->fullname,
            'division' => $request->division,
            'position' => $request->position,
        ]);


        if ($request->hasFile('photo')) {
            $userid = str_pad($profile->user->id, 6, '0', STR_PAD_LEFT);
            $prefix = config('custom.prefix_img_user.profile');
            $filename = "{$prefix}{$userid}.png";

            // Convert to PNG and resize to width 512px (height auto, keeps aspect ratio)
            $image = $request->file('image');

            // Create an image manager
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('photo')->getPathname())
                ->scale(height: 300)
                ->toPng();

            //uncomment below if you want to save the image in storage
            // Save to storage/app/public/img
            Storage::disk('public')->put("img/{$filename}", $image);

            // this will save the image in public/img
            //$publicPath = public_path("img");

            //if (!File::exists($publicPath)) {
            //File::makeDirectory($publicPath, 0755, true);
            //}

            //$image->save("{$publicPath}/{$filename}");

            // Save the path in DB
            $profile->update([
                'img_profile_path' => "img/{$filename}",
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

}
