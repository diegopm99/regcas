<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoPlla extends Model
{
    use HasFactory;
    protected $table = 't_pera_concep_plla';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];

    public function tipoConcepto(){
        return TipoConcepto::where([
            'uejcodigo' => $this->uejcodigo,
            'tcocodigo' => $this->tcocodigo,
        ])->first();
    }

    public function ente(){
        return Ente::where([
            'entcodigo' => $this->entcodgirar,
            'uejcodigo' => $this->uejcodigo,
        ])->first();
    }
}
