<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 't_sunat_tipdocs';
    protected $primaryKey = 'codtipdoc';
    public $timestamps = false;
    protected $guarded = [];
}
