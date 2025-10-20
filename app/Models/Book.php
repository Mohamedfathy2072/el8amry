<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'car_id',
        'user_id',
        'address',
        'appointment_date',
        'appointment_time',
        'name',
        'phone'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
