<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donation_id',
        'status',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
