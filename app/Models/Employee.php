<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';
    public $incrementing = false; 
    protected $keyType = 'int';
    
    // 自動タイムスタンプ（created_at, updated_at）を無効にする
    public $timestamps = false;
}
