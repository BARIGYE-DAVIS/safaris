<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    use HasFactory;

    // Table name is 'subscribers' by Laravel naming convention,
    // so this is optional but explicit for clarity.
    protected $table = 'subscribers';

    // Only allow mass assignment of the email field.
    protected $fillable = [
        'email',
    ];
}