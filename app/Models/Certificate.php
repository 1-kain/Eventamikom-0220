<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['transaction_id', 'certificate_number'];

    // Relasi balik ke transaksi untuk mengambil nama peserta & event
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}