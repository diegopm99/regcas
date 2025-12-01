<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConcepto extends Model
{
    use HasFactory;
    protected $table = 't_perh_tipos_concep';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [];
}
