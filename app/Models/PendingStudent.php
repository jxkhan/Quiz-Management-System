<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingStudent extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'name',
        'email',
        'cv_path',  // File path for the CV
        'status',   // Default to 'pending', can be 'rejected'
    ];
}

