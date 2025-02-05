<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
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
            'username' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        
        return response()->json(
            $user, 
         200);
    }

    public function update(User $users) {

    }
}