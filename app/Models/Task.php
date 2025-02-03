<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    // protected $fillable = ['user_id', 'title', 'description', 'completed', 'due_date'];

    protected $fillable = ['title', 'description', 'completed', 'due_date'];

}
