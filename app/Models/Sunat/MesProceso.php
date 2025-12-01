<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesProceso extends Model
{
    use HasFactory;
    protected $table = 't_prem_mesproceso';
    protected $primaryKey = 'mesproc';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];
}
