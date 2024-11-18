<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'tipo', 'numero', 'fecha', 'total', 'status', 'metodo_pago'];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
