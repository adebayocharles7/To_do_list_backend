<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;


//Route::middleware("auth:sanctum")->group(function () {});



Route::post('login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);

// Route::apiResource('tasks', TaskController::class);
 
Route::get('/user', function (Request $request) {
    return $request->user();
});
//->middleware('auth:sanctum');

Route::get('/task', function (Request $request) {
    return $request->task();
});
//->middleware('auth:sanctum');

/*
Route::get('tasks/{id}', function($id) {
    return Task::find($id);
});
*/

/* Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'show']);
}); */



/*Route::get('/tasks/{user_id}', function (Request $request) {
    $user = $request->user();
    $tasks = Task::where('user_id', $user->id)->get();

    return response()->json($tasks);
});
*/

// Route::get('/tasks/{user_id}', [TaskController::class, 'getUserTasks']);

/*
Route::get('/users/{user}/tasks', [TaskController::class, 'getSingleUserTask']); 
*/

Route::get('admin/tasks', [TaskController::class, 'getAllTasks'])
->middleware('auth:sanctum');

Route::get('/tasks/first/{user_id}', [TaskController::class, 'getFirstUserTask']);
// Retrieve single user's task by criteria (first)



Route::middleware('auth:sanctum')->get('user/tasks', [TaskController::class, 'getTasks']);
// retrieve the tasks by user_id

Route::middleware('auth:sanctum')->post('/user/{id}/tasks', [TaskController::class, 'createTask']);

Route::put('/tasks/{task_id}/', action: [TaskController::class, 'updateTask'])->middleware('auth:sanctum');

Route::put('users/{user_id}/task/{task_id}/', action: [TaskController::class, 'updateUserTask']);

Route::middleware('auth:sanctum')->delete('/tasks/{id}', [TaskController::class, 'deleteTask']); // Delete a single user's task

Route::delete('/tasks', [TaskController::class, 'task']); // Delete all tasks belonging to a user