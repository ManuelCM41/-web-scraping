<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Client\Article;
use App\Livewire\Client\ArticleDetail;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Article::class)->name('articles');
Route::get('/{titulo}', ArticleDetail::class)->name('article-detail');

require_once __DIR__ . '/jetstream.php';
