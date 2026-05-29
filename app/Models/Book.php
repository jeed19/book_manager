<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // 主キーの列名を指定（デフォルトの 'id' から変更）
    protected $primaryKey = 'isbn';

    // もし主キーが自動インクリメント（連番）の数字ではない場合は以下も必要
    public $incrementing = false; 
}
