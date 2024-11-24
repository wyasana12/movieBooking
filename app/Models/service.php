<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'price',
    ];

    public function booking()
    {
        $this->belongsToMany(Booking::class, 'booking-service')->withPivot('jumlah')->withTimestamps();
    }
}
