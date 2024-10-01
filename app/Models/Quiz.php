<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'schedule_at',
        'expires_at',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
