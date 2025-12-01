<?php

namespace App\Http\Controllers;

use App\Models\Sunat\FuenteFto;
use App\Traits\ApiResponse;

class FuenteFtoController extends Controller
{
    use ApiResponse;

    public function filterByAnoProc($anoproc) {
        $data = FuenteFto::orderBy('ftocodigo', 'asc')->where('anoproc', $anoproc)->get();
        return $this->successResponse('Fuentes encontradas correctamente', $data);
    }
}
