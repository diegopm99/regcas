<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaContable extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 't_sunat_ctas_conts';
    protected $primaryKey = 'codcuenta';
    public $timestamps = false;
    protected $guarded = [];
}
