<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'receipt_number',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
}
