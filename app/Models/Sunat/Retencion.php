<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    use HasFactory;
    protected $table = 't_test_cpdetreten';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];

    public function conceptoPlla(){
        return ConceptoPlla::where([
            'anoproc' => $this->anoproc,
            'uejcodigo' => $this->uejcodigo,
            'tcocodigo' => $this->tcocodigo,
            'concodcplla' => $this->concodcplla,
        ])->first();
    }
}
