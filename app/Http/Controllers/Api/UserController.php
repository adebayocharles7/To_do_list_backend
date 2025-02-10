<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = new User;
        if($users->count() > 0) {
            return UserResource::collection(resource: $users->paginate(7));
        } 
        else {
            return response()->json(['message' => 'No record available', 200], );
        }
    }

    public function store(Request $request) {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => "required|string|max:255",
            'last_name' => "required|string|max:255",
            'email' => 'required|string|email|max:255|unique:users',          
            'username' => "required",
            'password' => "required|string|min:8|confirmed", // password_confirmation
        ]);
         
        if ($validator->fails()) {
            return response()->json([
                'message' => 'All the fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        // Create the new user
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        
        // Dispatch the Registered event
        //event(new Registered($user));

        // return a success response and user data
        return response()->json([
            'message' => 'Registration successful',
            'user' => $user, 
            200
        ]);
    }

    public function update(Request $request, User $user) {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => "sometimes|required|string|max:255",
            'last_name' => "sometimes|required|string|max:255",
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => "sometimes|required|string|max:255|unique:users,username," . $user->id,
            'password' => "sometimes|required|string|min:8|confirmed", // password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $validator->messages(),
            ], 422);
        }

        // Update the user
        $user->update($request->only(['first_name', 'last_name', 'email', 'username', 'password']));

        // If password is provided, hash it before saving
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        // Return a success response and updated user data
        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ], 200);
    }

    
}