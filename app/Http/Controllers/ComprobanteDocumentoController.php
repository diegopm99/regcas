<?php

namespace App\Http\Controllers;

use App\Models\Sunat\AnoProceso;
use App\Models\Sunat\ComprobanteDocumento;
use App\Models\Sunat\MesProceso;
use App\Models\Sunat\RetencionSunat;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ComprobanteDocumentoController extends Controller
{
    use ApiResponse;

    public function view()
    {
        $retenciones = RetencionSunat::all();
        $anios = AnoProceso::orderBy('anoproc', 'desc')->get();
        $meses = MesProceso::orderBy('mesproc', 'desc')->get();
        return view('sunat.documento.index', compact([
            'retenciones',
            'anios',
            'meses'
        ]));
    }

    public function index()
    {
        try {
            $data = ComprobanteDocumento::with([
                'cuentaContable',
                'tipoDocumento',
                'tipoRetencion',
                'annoProceso',
                'mesProceso'
            ])->orderBy('fecha_comp', 'desc')
                ->get();
            $data->each(function ($documento) {
                $documento->setAttribute('comprobante', $documento->comprobante());
                $documento->setAttribute('fuente', $documento->fuenteFto());
            });
            return $this->successResponse("Documentos encontrados exitosamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un error en el servidor', 500, $e->getMessage());
        }
    }

    public function filterByComprobante(Request $request)
    {
        try {
            if (!$request->filled(['anoproc', 'cpcfuente', 'cpcnumero', 'uejcodigo'])) {
                return response()->json(['data' => []]);
            }
            $data = ComprobanteDocumento::withTrashed()
                ->with([
                    'cuentaContable',
                    'tipoDocumento',
                    'tipoRetencion',
                    'annoProceso',
                    'mesProceso'
                ])
                ->where([
                    'anno_comp' => $request->anoproc,
                    'ff_comp' => $request->cpcfuente,
                    'num_comp' => $request->cpcnumero,
                    'uejcodigo' => $request->uejcodigo
                ])->get();
            return $this->successResponse('Documentos encontrados exitosamente', $data);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error en el servidor', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'num-comp' => [
                    'required',
                    Rule::unique('t_sunat_cpdocs', 'num_comp')
                        ->where(function ($query) use ($request) {
                            return $query
                                ->where('serie_doc', $request['nro-serie'])
                                ->where('num_doc', $request['nro-documento'])
                                ->where('fecha_doc', $request['fecha-emision'])
                                ->where('ruc', $request['ruc']);
                        }),
                ],
            ], [
                'num-comp.unique' => 'Documento registrado previamente'
            ]);

            ComprobanteDocumento::create([
                'uejcodigo' => $request['uejcodigo'],
                'anno_comp' => $request['anno-comp'],
                'num_comp' => $request['num-comp'],
                'ff_comp' => $request['ff-comp'],
                'fuente_comp' => $request['fuente-comp'],
                'fecha_comp' => Carbon::parse($request['fecha-comp'])->toDateString(),
                'num_siaf' => $request['num-siaf'],

                'ruc' => $request['ruc'],
                'proveedor' => $request['proveedor'],

                'num_orden' => $request['nro-orden'],
                'fecha_orden' => Carbon::parse($request['fecha-orden'])->toDateString(),
                'torden' => $request['tipo-orden'],
                'ctacont' => $request['cuenta'],

                'tdoc' => $request['tipo-documento'],
                'serie_doc' => $request['nro-serie'],
                'num_doc' => $request['nro-documento'],
                'fecha_doc' => Carbon::parse($request['fecha-emision'])->toDateString(),
                'fecha_venc' => Carbon::parse($request['fecha-venc'])->toDateString(),
                'v_grav' => $request['gravable'],
                'v_nograv' => $request['no-gravable'],
                'igv' => $request['igv'],
                'penalidad' => $request['penalidad'],
                'garantia' => $request['garantia'],
                'total' => $request['total'],

                'tretenc' => $request['tipo-retenc'],
                'serie_retenc' => $request['serie-retenc'],
                'num_retenc' => $request['nro-retenc'],
                'fecha_retenc' => $request['fecha-retenc']
                    ? Carbon::createFromFormat('Y-m-d', $request['fecha-retenc'])->toDateString()
                    : null,
                'retencion' => $request['retencion'],
                'coactivo' => $request['coactivo'],
                'detraccion' => $request['detraccion'],
                'neto' => $request['neto'],
                'obs' => $request['observaciones'],
                'anno' => Carbon::now()->year,
                'mes' => Carbon::now()->format('m'),
            ]);
            DB::commit();
            return $this->successResponse('Documento registrado con éxito');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return $this->errorResponse($firstError, 200, 'Verificar los datos ingresados');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al registrar el documento', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'num-comp' => [
                    'required',
                    Rule::unique('t_sunat_cpdocs', 'num_comp')
                        ->where(function ($query) use ($request) {
                            return $query
                                ->where('serie_doc', $request['nro-serie'])
                                ->where('num_doc', $request['nro-documento'])
                                ->where('fecha_doc', $request['fecha-emision'])
                                ->where('ruc', $request['ruc']);
                        })->ignore($request->codcpdoc, 'codcpdoc')
                ],
            ], [
                'num-comp.unique' => 'El documento ya se encuentra registrado'
            ]);
            $comprobante = ComprobanteDocumento::findOrFail($request['codcpdoc']);
            $comprobante->update([
                'num_orden' => $request['nro-orden'],
                'fecha_orden' => Carbon::parse($request['fecha-orden'])->toDateString(),
                'torden' => $request['tipo-orden'],
                'ctacont' => $request['cuenta'],

                'tdoc' => $request['tipo-documento'],
                'serie_doc' => $request['nro-serie'],
                'num_doc' => $request['nro-documento'],
                'fecha_doc' => Carbon::parse($request['fecha-emision'])->toDateString(),
                'fecha_venc' => Carbon::parse($request['fecha-venc'])->toDateString(),
                'v_grav' => $request['gravable'],
                'v_nograv' => $request['no-gravable'],
                'igv' => $request['igv'],
                'penalidad' => $request['penalidad'],
                'garantia' => $request['garantia'],
                'total' => $request['total'],

                'tretenc' => $request['tipo-retenc'],
                'serie_retenc' => $request['serie-retenc'],
                'num_retenc' => $request['nro-retenc'],
                'fecha_retenc' => $request['fecha-retenc']
                    ? Carbon::createFromFormat('Y-m-d', $request['fecha-retenc'])->toDateString()
                    : null,
                'retencion' => $request['retencion'],
                'coactivo' => $request['coactivo'],
                'detraccion' => $request['detraccion'],
                'neto' => $request['neto'],
                'obs' => $request['observaciones'],
                'anno' => Carbon::now()->year,
                'mes' => Carbon::now()->format('m'),
            ]);
            DB::commit();
            return $this->successResponse('Datos actualizados con éxito');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return $this->errorResponse($firstError, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar los datos', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            ComprobanteDocumento::find($id)->delete();
            return $this->successResponse('Documento eliminado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error en el servidor', 500, $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            ComprobanteDocumento::withTrashed()->where('codcpdoc', $id)->restore();
            return $this->successResponse('Registro restaurado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al restaurar el registro', 500, $e->getMessage());
        }
    }
}
