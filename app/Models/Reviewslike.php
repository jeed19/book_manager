<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewslike extends Model
{
    use HasFactory;

    protected $table = 'review_likes';

    public $timestamps = false; 

    protected $fillable = [
        'employee_id',
        'review_id',
    ];
}
