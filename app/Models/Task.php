<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'priority',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'deadline'  => 'date',
            'completed' => 'boolean',
        ];
    }
}
