<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetencionSunat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 't_sunat_retencs';
    protected $primaryKey = 'codretenc';
    public $timestamps = false;
    protected $guarded = [];
}
