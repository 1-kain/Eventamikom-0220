<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'user_id',
    'ticket_code',    // 🌟 Tambahan baru
    'event_id', 'order_id', 'ticket_code', 'customer_name', 'customer_email', 
    'customer_phone', 'total_price', 'status', 'is_scanned', 'snap_token'
];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}