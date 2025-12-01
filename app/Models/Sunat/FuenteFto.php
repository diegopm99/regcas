<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuenteFto extends Model
{
    use HasFactory;
    protected $table = 't_prem_ftefto';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];
}
