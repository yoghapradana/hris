<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;




class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('main.profile-index', compact('user'));
    }



    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:100',
            'division' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            // 'entry_date' is managed by HR, so no update
        ]);

        $profile = auth()->user()->profile;

        $profile->update([
            'fullname' => $request->fullname,
            'division' => $request->division,
            'position' => $request->position,
        ]);


        if ($request->hasFile('photo')) {
            $username = auth()->user()->username;
            $filename = "img_profile%-%-{$username}.png";

            // Convert to PNG and resize to width 512px (height auto, keeps aspect ratio)
            $image = $request->file('image');

            // Create an image manager
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('photo')->getPathname())
                ->scale(height: 300)
                ->toPng();

            //uncomment below if you want to save the image in storage
            // Save to storage/app/public/img
            //Storage::disk('public')->put("img/{$filename}", $image);

            // this will save the image in public/img
            $publicPath = public_path("img");

            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }

            $image->save("{$publicPath}/{$filename}");

            // Save the path in DB
            $profile->update([
                'img_pic_path' => "img/{$filename}",
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
