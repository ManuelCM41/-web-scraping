<?php

use App\Livewire\Payment\PageVentaResultado;
use App\Livewire\PagePlanes;
use App\Livewire\Admin\Articles;
use Illuminate\Support\Facades\Route;
use App\Livewire\Client\Article;
use App\Livewire\Client\ArticleDetail;
use App\Livewire\PageClient;
use App\Livewire\PageVoucher;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Article::class)->name('articles');

Route::get('/planes', PagePlanes::class)->name('planes');
Route::get('/cliente/{total}', PageClient::class)->name('cliente');

// RUTAS PARA EL PAGO DE PAYPAL Y YAPE
Route::get('/paypal/pay', 'App\Livewire\Payment\PaymenPaypal@pagarPaypal');
Route::get('/paypal/status', 'App\Livewire\Payment\PaymenPaypal@PaypalStatus');
Route::get('/results', PageVentaResultado::class)->name('venta-resultado');

// RUTA PARA GENERAR EL VOUCHER EN PDF
Route::get('/pdf/visualizar', [PageVoucher::class, 'validarProforma'])->name('voucher.visualizar');

Route::get('/{titulo}', ArticleDetail::class)->name('article-detail');

Route::get('/articulos/pdf', [Articles::class, 'createPDF']);

require_once __DIR__ . '/jetstream.php';
