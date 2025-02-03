<?php

namespace App\Http\Controllers\Api;

use the;
use Exception;
use Throwable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /*
    public function index() {
        $tasks = Task::get();
        if($tasks->count() > 0) {
            return TaskResource::collection(resource: $tasks);
        } 
        else {
            return response()->json(['message' => 'No record available', 200], );
        }
    }
*/

    public function getAllTasks() {

        $user = Auth::user();

        // Check if the user is authenticated and is an admin
        if (!$user || $user->is_admin !== 1) {
            return response()->json([
                'message' => 'Unauthorized: Access is restricted to admins only.'
            ], 403);
        }

        $tasks = Task::all();
        if ($tasks->count() > 0) {
           return response()->json($tasks); 
        }
        else {
            return response()->json(['message' => 'No record available'], 200);
        }
    
    } 

    public function createTask(Request $request) {
        
        $user = Auth::user(); //Retrieving authenticated User */

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 403);
        }
        
        try {
        // Validate the incoming request
        $validatedData = $request->validate( [
            'title' => "required|string|max:255",
            'description' => "required|string|max:255",
            'completed' => "required|boolean", 
            'due_date' => "required|date"
        ]);
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'All the fields are mandatory',
                'errors' => $e->errors(),
            ], 422);
            
        }

        try {
        // Create a new task for the user
        $task = new Task();
        $task->user_id = $user->id;
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->completed = $validatedData['completed'];
        $task->due_date = $validatedData['due_date'];
        $task->save();

        return response()->json([
            'message' => 'Task created successfully'
        ], 200);
        } catch (Exception $e) {
            //Handle any exceptions during task creation
            return response()->json([
                    'message' => 'Failed to create task',
                    'error' => $e->getMessage(),
                ], 500);
        }
    }
    


    /*
    public function show($id)
    {
        return Task::find($id);
    } */

    /*
    public function getAllUserTasks(	) {
        /* $user = auth()->user(); //Retrieving authenticated User 

        $user = Auth::user();

        // Check if the user is authenticated and is an admin
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }
        $tasks = Task::get();

        return response()->json ($tasks); 

    }     */

    public function getTasks() {
       try {
        $user = Auth::user(); //Retrieving authenticated User */

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 403);
        }

        // Retrieve tasks belonging to the authenticated user
        $tasks = Task::where('user_id', $user->id)->get();

        return response()->json($tasks); 
        } catch (Throwable $e) {
        return response()->json(['message' => 'No record available'], 500);
        }
} 

    
    /*public function getFirstUserTask($user_id) {
        

        $task = Task::where('user_id', $user_id)->first();

        if (!$task) {
        
        return response()->json(['message' => 'No task matches the criteria'], 404);
        }

        return response()->json($task, 200);
 
    } */

public function updateTask(Request $request, $task_id) {
    // Validate request data
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'completed' => 'required|boolean',
        'due_date' => 'required|date',
    ]);

    // Get the authenticated user
        $user = Auth::user();

        // Find the task by ID and ensure it belongs to the user
        $task = Task::where('id', $task_id)->where('user_id', $user->id)->first();

    if (!$task) {
        return response()->json([
            'message' => 'Task not found.',
        ], 404);
    }

    // Update the task
    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'due_date' => $request->due_date,
    ]);

    return response()->json([
        'message' => 'Task updated successfully.',
        'task' => $task,
    ], 200);

}

    public function deleteTask($id, Request $request) {
    // Get the authenticated user
    $user = $request->user();

    // Find the task by ID belonging to the authenticated user
    $task = Task::find($id)
                ->where('user_id', $user->id) //Ensures the task belongs to the user
                ->first();

    if (!$task) {
        return response()->json(['message' => 'Task not found or not authorized to delete'], 404);
    }

    // Delete the task
        $task->delete();
        return response()->json([
            'message' => 'Task deleted succesfully',
        ], 200);
    }
    
}
