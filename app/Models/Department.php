<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // 主キーのカラム名
    protected $primaryKey = 'department_id';

    // 主キーは連番（自動インクリメント）ではない
    public $incrementing = false;

    // 主キーの型（文字列の場合）
    protected $keyType = 'string';

    // タイムスタンプを使用しない場合
    public $timestamps = false;
}