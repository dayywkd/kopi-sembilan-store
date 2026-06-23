<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_name',
        'business_name',
        'email',
        'phone',
        'message'
    ];
}
