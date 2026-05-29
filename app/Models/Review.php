<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'employee_id',
        'isbn',
        'recommended_level',
        'title',
        'comment',
    ];

    public $incrementing = false; // 自動増分IDを無効化
    protected $primaryKey = 'review_id';
}
