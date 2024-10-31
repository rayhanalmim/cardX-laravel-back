<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone_number' => 'nullable|string|max:20', // Add phone number validation
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Store the profile image if provided
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $signature_name = rand(11, 99999) . '_' . $image->getClientOriginalName(); // Generate a unique filename
            $image->move(public_path('storage/profile_images'), $signature_name); // Move the image to the specified directory
            $profileImagePath = 'storage/profile_images/' . $signature_name; // Set the path for the database
        }
    
        // Create the user with the profile image URL and phone number
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $profileImagePath ? url($profileImagePath) : null,
            'phone_number' => $request->phone_number, // Store phone number
        ]);
    
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    
}
