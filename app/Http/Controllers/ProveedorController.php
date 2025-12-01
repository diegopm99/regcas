<?php

namespace App\Http\Controllers;

use App\Models\Sunat\Proveedor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProveedorController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Proveedor::create($request->only('entcodigo', 'uejcodigo', 'pronombres', 'pronumruc'));
            DB::commit();
            return $this->successResponse('Proveedor registrado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Ocurrió un error al registrar el proveedor', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate(
                [
                    'entcodigo' => ['required'],
                    'uejcodigo' => ['required'],
                    'pronumruc' => [
                        'required',
                        Rule::unique('t_logm_provee', 'pronumruc')
                    ],
                ],
                [
                    'pronumruc.unique' => 'Este número de RUC ya se encuentra registrado'
                ]
            );

            $proveedor = Proveedor::where([
                'entcodigo' => $request->entcodigo,
                'uejcodigo' => $request->uejcodigo
            ])->whereNull('pronumruc')->firstOrFail();

            $proveedor->update($request->only('pronumruc'));
            return $this->successResponse('Número de RUC almacenado exitosamente');
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return $this->errorResponse($firstError, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al actualizar los datos', 500, $e->getMessage());
        }
    }
}
