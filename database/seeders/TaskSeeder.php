<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all users
        $users = User::all();
         
        // Create ten fake tasks, one for each user
        foreach ($users as $user) {
            Task::factory()->count(10)->create(['user_id' => $user->id]);
        }    
    }
}
