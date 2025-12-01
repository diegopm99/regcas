<?php

namespace App\Http\Controllers;

use App\Models\Sunat\CuentaContable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CuentaContableController extends Controller
{

    use ApiResponse;

    public function view()
    {
        return view('sunat.mantenimiento.cuentas');
    }

    public function index()
    {
        $data = CuentaContable::orderBy('clasificador', 'asc')->get();
        return $this->successResponse('Cuentas obtenidas correctamente', $data);
    }

    public function indexWithTrashed()
    {
        $data = CuentaContable::withTrashed()->orderBy('clasificador', 'asc')->get();
        return $this->successResponse('Cuentas obtenidas correctamente', $data);
    }

    public function filterByTipoOrden($tipo)
    {
        try {
            $data = CuentaContable::where('tipo_orden', $tipo)->get();
            return $this->successResponse("Cuentas contables encontradas correctamente", $data);
        } catch (\Exception $e) {
            return $this->errorResponse("Ocurrió un error en el servidor", 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            CuentaContable::create($request->all());
            return $this->successResponse('Cuenta registrada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al registrar la cuenta', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $cuenta = CuentaContable::findOrFail($request['codcuenta']);
            $cuenta->update($request->only('clasificador', 'tipo_orden', 'cuenta'));
            return $this->successResponse('Cuenta actualizada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al actualizar la cuenta', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            CuentaContable::find($id)->delete();
            return $this->successResponse('Datos eliminados correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al eliminar los datos', 500, $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            CuentaContable::withTrashed()->where('codcuenta', $id)->restore();
            return $this->successResponse('Registro restaurado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al restaurar el registro', 500, $e->getMessage());
        }
    }
}
