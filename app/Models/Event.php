<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Event extends Model
{
    //
    protected $fillable = [
        'user_id',
        'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
}