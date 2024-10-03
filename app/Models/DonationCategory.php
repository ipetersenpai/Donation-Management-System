<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'description', 'about', 'link', 'created_at', 'updated_at'];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'category_id');
    }
}
