<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'allocated_amount',
    ];

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id');
    }
}
