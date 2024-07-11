<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'method',
        'transaction_id',
        'status',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
}
