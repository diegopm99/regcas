<?php

namespace App\Http\Controllers;

use App\Models\Sunat\TipoDocumento;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    use ApiResponse;

    public function view() {
        return view('sunat.mantenimiento.tdocumentos');
    }

    public function index()
    {
        try {
            $data = TipoDocumento::orderBy('coddocsunat', 'asc')->get();
            return $this->successResponse("Tipos de documentos encontrados exitosamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse("Ocurrió un error en el servidor", 500, $e->getMessage());
        }
    }

    public function indexWithTrashed()
    {
        try {
            $data = TipoDocumento::withTrashed()->orderBy('coddocsunat', 'asc')->get();
            return $this->successResponse("Tipos de documentos encontrados exitosamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse("Ocurrió un error en el servidor", 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            TipoDocumento::create($request->all());
            return $this->successResponse('Tipo de documento registrado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al registrar los datos', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $tdocumento = TipoDocumento::findOrFail($request['codtipdoc']);
            $tdocumento->update($request->only('descripcion'));
            return $this->successResponse('Tipo de documento actualizado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al actualizar los datos', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            TipoDocumento::where('codtipdoc', $id)->delete();
            return $this->successResponse('Datos eliminados correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al eliminar los datos', 500, $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            TipoDocumento::withTrashed()->where('codtipdoc', $id)->restore();
            return $this->successResponse('Registro restaurado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al restaurar el registro', 500, $e->getMessage());
        }
    }
}
