<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
        'date' => 'date',
    ];

    public function times()
    {
        return $this->hasMany(ScheduleTime::class);
    }
}
