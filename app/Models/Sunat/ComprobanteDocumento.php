<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComprobanteDocumento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 't_sunat_cpdocs';
    protected $primaryKey = 'codcpdoc';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'fecha_comp' => 'datetime:d-m-Y',
        'fecha_orden' => 'datetime:d-m-Y',
        'fecha_doc' => 'datetime:d-m-Y',
        'fecha_venc' => 'datetime:d-m-Y',
        'fecha_retenc' => 'datetime:d-m-Y',
        'v_grav' => 'decimal:2',
        'v_nograv' => 'decimal:2',
        'igv' => 'decimal:2',
        'penalidad' => 'decimal:2',
        'garantia' => 'decimal:2',
        'total' => 'decimal:2',
        'retencion' => 'decimal:2',
        'coactivo' => 'decimal:2',
        'detraccion' => 'decimal:2',
        'neto' => 'decimal:2',
    ];

    public function comprobante()
    {
        return ComprobanteCabecera::where([
            'uejcodigo' => $this->uejcodigo,
            'anoproc' => $this->anno_comp,
            'cpcnumero' => $this->num_comp,
            'cpcfuente' => $this->ff_comp
        ])->first();
    }

    public function fuenteFto()
    {
        return FuenteFto::where([
            'uejcodigo' => $this->uejcodigo,
            'anoproc' => $this->anno_comp,
            'ftocodigo' => $this->ff_comp,
        ])->first();
    }

    public function cuentaContable()
    {
        return $this->belongsTo(CuentaContable::class, 'ctacont', 'codcuenta');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tdoc', 'codtipdoc');
    }

    public function tipoRetencion()
    {
        return $this->belongsTo(RetencionSunat::class, 'tretenc', 'codretenc');
    }

    public function annoProceso()
    {
        return $this->belongsTo(AnoProceso::class, 'anno', 'anoproc');
    }

    public function mesProceso()
    {
        return $this->belongsTo(MesProceso::class, 'mes', 'mesproc');
    }
}
