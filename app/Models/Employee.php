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

    const DISPLAY_NAME_MAX = 32;
    const RAW_PASSWORD_MIN = 8;
    const RAW_PASSWORD_MAX = 32;
    
    // locked_at カラムを自動的に日付・時刻オブジェクト（Carbon）に変換する
    protected $casts = [
        'locked_at' => 'datetime',
    ];
}
