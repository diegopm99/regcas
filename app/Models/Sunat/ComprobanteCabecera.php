<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteCabecera extends Model
{
    use HasFactory;

    protected $table = 't_test_cppagocab';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [];

    protected $casts = [
        'cpcfecha' => 'datetime:d-m-Y',
    ];

    public function proveedor()
    {
        return Proveedor::where([
            'entcodigo' => $this->entcodigo,
            'uejcodigo' => $this->uejcodigo,
        ])->first();
    }

    public function ente()
    {
        return Ente::where([
            'entcodigo' => $this->entcodigo,
            'uejcodigo' => $this->uejcodigo,
        ])->first();
    }

    public function fuente()
    {
        return FuenteFto::where([
            'ftocodigo' => $this->cpcfuente,
            'anoproc'      => $this->anoproc,
            'uejcodigo' => $this->uejcodigo,
        ])->first();
    }

    public function retenciones()
    {
        return Retencion::where([
            'cpcnumero' => $this->cpcnumero,
            'cpcfuente' => $this->cpcfuente,
            'anoproc'      => $this->anoproc,
            'uejcodigo' => $this->uejcodigo,
        ])->get();
    }

    public function documentos()
    {
        return ComprobanteDocumento::where([
            'num_comp' => $this->cpcnumero,
            'ff_comp' => $this->cpcfuente,
            'anno_comp' => $this->anoproc,
            'uejcodigo' => $this->uejcodigo,
        ])->get();
    }
}
