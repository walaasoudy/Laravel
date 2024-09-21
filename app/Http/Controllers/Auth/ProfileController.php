<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;

use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfile(ProfileUpdateRequest $request){
        $user = $request->user();
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
        }
        $user->update($request->validated());
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'house_no' => $user->house_no,
            'city' => $user->city,
            'photo_url' => $user->photo ? asset('storage/'.$user->photo) : null,];
        return ApiResponse::sendResponse(200, 'Profile updated successfully',null);
    }

    public function getProfile(Request $request){
        $user = $request->user();
        return ApiResponse::sendResponse(200, 'User profile data', new UserResource($user));
    }

}
