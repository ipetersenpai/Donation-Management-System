<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'fund_allocation';

    protected $fillable = [
        'category_id',
        'category_name',
        'project_name',
        'allocated_amount',
    ];

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id');
    }
}
