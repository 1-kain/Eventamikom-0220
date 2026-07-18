<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organizer_id',
        'event_id',
        'rating',
        'comment',
    ];

    // Relasi ke User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Organizer (User dengan role organizer)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}