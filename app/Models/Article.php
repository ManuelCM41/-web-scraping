<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'url',
        'urlPrincipal',
        'path',
        'extracto',
        'categoria',
        'imagen',
        'autor',
        'fecha',
        'avatar',
    ];
}
