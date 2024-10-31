<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve only the cards for the authenticated user, sorted by newest first
        $cards = Auth::user()->cards()->orderBy('created_at', 'desc')->get();
    
        foreach ($cards as $card) {
            // Update the image URL to match the expected format
            $card->image_url = $card->image 
                ? url('' . $card->image) 
                : null;
            
            // Add status code to each card
            $card->status_code = 200;
        }
    
        return response()->json($cards, 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'companyName' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phoneNumber' => 'nullable|array', // Accept phoneNumber as an array
            'phoneNumber.*' => 'string|max:255', // Each phone number must be a string
            'mobileNumber' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Ensure the user is authenticated
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $signature_name = rand(11, 99999) . '_' . $image->getClientOriginalName(); // Generate a unique filename
            $image->move(public_path('storage/images'), $signature_name); // Move the image to the specified directory
            $validated['image'] = 'storage/images/' . $signature_name; // Set the path for the database
        }
    
        // Create a card for the authenticated user
        $card = $user->cards()->create($validated);
    
        $card->image_url = url($card->image); // Generate the correct URL for the image
        $card->status_code = 201;
    
        return response()->json(['message' => 'Card created successfully.', 'card' => $card], 201);
    }
    
    


    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        // Ensure the card belongs to the authenticated user
        $this->authorize('view', $card);

        $card->image_url = Storage::url($card->image);
        $card->status_code = 200;

        return response()->json($card, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        // Ensure the user has permission to update the card
        $this->authorize('update', $card);

        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'nullable|email',
            'companyName' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'phoneNumber' => 'nullable|array', // Accept phoneNumber as an array
            'phoneNumber.*' => 'string|max:255', // Each phone number must be a string
            'mobileNumber' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $signature_name = rand(11, 99999) . '_' . $image->getClientOriginalName(); // Generate a unique filename
            $image->move(public_path('storage/images'), $signature_name); // Move the image to the specified directory
            $validated['image'] = 'storage/images/' . $signature_name; // Set the path for the database

            // Delete old image if it exists
            if ($card->image) {
                $oldImagePath = public_path($card->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }

        // Update the card with the validated data
        $card->update(array_filter($validated));

        // Set the image URL and status code
        $card->image_url = $card->image ? url($card->image) : null;
        $card->status_code = 200;

        return response()->json(['message' => 'Card updated successfully.', 'card' => $card], 200);
    }


    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        // $this->authorize('delete', $card);

        $card->delete();

        return response()->json(['message' => 'Card deleted successfully.'], 200); // Change status code to 200
    }
}
