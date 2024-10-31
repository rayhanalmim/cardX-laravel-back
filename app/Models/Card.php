<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'companyName', 'designation', 'name', 'phoneNumber', 
        'mobileNumber', 'address', 'image', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cast phoneNumber to array
    protected $casts = [
        'phoneNumber' => 'array',
    ];
}

