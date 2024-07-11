<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'period_start',
        'period_end',
    ];

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class, 'report_id');
    }
}
