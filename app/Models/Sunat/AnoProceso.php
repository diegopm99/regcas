<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnoProceso extends Model
{
    use HasFactory;
    protected $table = 't_prem_anoproceso';
    protected $primaryKey = 'anoproc';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];
}
