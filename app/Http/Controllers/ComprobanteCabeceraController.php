<?php

namespace App\Http\Controllers;

use App\Models\Sunat\AnoProceso;
use App\Models\Sunat\ComprobanteCabecera;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ComprobanteCabeceraController extends Controller
{

    use ApiResponse;

    public function index()
    {
        $anios = AnoProceso::orderBy('anoproc', 'desc')->get();
        return view('sunat.comprobante.index', compact('anios'));
    }

    public function find(Request $request)
    {
        try {
            $data = ComprobanteCabecera::where([
                'anoproc' => $request->anoproc,
                'cpcfuente' => $request->cpcfuente,
                'cpcnumero' => $request->cpcnumero
            ])->first();

            if ($data) {
                $data->fuente = $data->fuente();
                $data->proveedor = $data->proveedor();
                if(is_null($data->proveedor)){
                    $data->ente = $data->ente();
                }
                $data->retenciones = $data->retenciones();
                $data->retenciones->each(function ($retencion) {
                    $retencion->conceptoPlla = $retencion->conceptoPlla();
                });
                $data->retenciones->each(function ($retencion) {
                    if ($retencion->conceptoPlla) {
                        $retencion->conceptoPlla->tipoConcepto = $retencion->conceptoPlla->tipoConcepto();
                        $retencion->conceptoPlla->ente = $retencion->conceptoPlla->ente();
                    }
                });
            }

            return $this->successResponse('Comprobante encontrados correctamente', $data);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al buscar comprobante', 500, $e->getMessage());
        }
    }
}
