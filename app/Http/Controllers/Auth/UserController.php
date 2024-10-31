<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Get user data by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id); // This will throw a ModelNotFoundException if not found
            unset($user->password); // Hide the password field if necessary
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while retrieving the user data.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update user data and verification status by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id); // Find the user by ID
    
            // Validate the incoming request data, making all fields optional
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8', // Password validation with confirmation
                'phone_number' => 'nullable|string|max:20',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'isVerified' => 'nullable|boolean',
            ]);
    
            // Hash the password if it is being updated
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }
    
            // Handle image upload if provided
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = rand(11, 99999) . '_' . $image->getClientOriginalName(); // Generate a unique filename
                $imagePath = $image->move(public_path('storage/images'), $imageName); // Move the image to the specified directory
                $validated['profile_image'] = 'storage/images/' . $imageName; // Set the path for the database
    
                // Delete old image if it exists
                if ($user->profile_image) {
                    $oldImagePath = public_path($user->profile_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
    
            // Update the user with the validated data
            $user->update(array_filter($validated));
    
            // Set the full image URL in the response
            $user->profile_image_url = $user->profile_image ? url($user->profile_image) : null;
    
            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $user,
                'profile_image_url' => $user->profile_image_url // Explicitly include image URL in response
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating user data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
}
