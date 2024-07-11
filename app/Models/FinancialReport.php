<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'description',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
