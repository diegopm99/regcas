<?php

namespace App\Http\Controllers;

use App\Models\Sunat\RetencionSunat;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RetencionSunatController extends Controller
{
    use ApiResponse;

    public function view()
    {
        return view('sunat.mantenimiento.retenciones');
    }

    public function index()
    {
        try {
            $data = RetencionSunat::orderBy('descripcion', 'asc')->get();
            return $this->successResponse("Retenciones encontradas exitosamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse("Ocurrió un error en el servidor", 500, $e->getMessage());
        }
    }

    public function indexWithTrashed()
    {
        try {
            $data = RetencionSunat::withTrashed()->orderBy('descripcion', 'asc')->get();
            return $this->successResponse("Retenciones encontradas exitosamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse("Ocurrió un error en el servidor", 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            RetencionSunat::create($request->all());
            return $this->successResponse('Retención registrada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al registrar la retención', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $retencion = RetencionSunat::findOrFail($request['codretenc']);
            $retencion->update($request->only('descripcion', 'tipo', 'porcentaje'));
            return $this->successResponse('Retención actualizada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al actualizar la retención', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            RetencionSunat::find($id)->delete();
            return $this->successResponse('Datos eliminados correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al eliminar los datos', 500, $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            RetencionSunat::withTrashed()->where('codretenc', $id)->restore();
            return $this->successResponse('Registro restaurado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al restaurar el registro', 500, $e->getMessage());
        }
    }
}
