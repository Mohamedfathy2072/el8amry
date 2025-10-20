<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTime extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id', 'time'];

    protected $casts = [
        'time' => 'datetime:H:i',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
