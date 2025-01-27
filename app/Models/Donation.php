<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'non_member_full_name',
        'category_id',
        'payment_option',
        'amount',
        'reference_no',
    ];

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentGateways()
    {
        return $this->hasMany(PaymentGateway::class, 'donation_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'donation_id');
    }

}
