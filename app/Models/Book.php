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

    const ISBN_LENGTH = 13;
    const MAX_BOOK_NAME = 128;
    const MAX_AUTHOR_NAME = 128;
    const MAX_COVER_IMAGE = 256;
    const MAX_PUBLISHER = 128;
}
