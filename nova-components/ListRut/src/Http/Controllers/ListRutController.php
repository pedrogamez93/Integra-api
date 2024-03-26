<?php

namespace Meat\ListRut\Http\Controllers;

use App\Imports\SurveyUsersImport;
use App\UserRutList;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ListRutController
{
    const TYPE_SETTLEMENT = 1;
    const TYPE_POST = 2;
    const TYPE_RELEASE = 3;
    const TYPE_LABOR_OFERTA = 4;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function send(Request $request)
    {
        $file = $request['file'];
        if (!$request['name']) {
            return response()->json(['data' => ['message' => 'El campo Nombre es obligatorio']], 422);
        }
        if ($request['file'] == 'undefined') {
            return response()->json(['data' => ['message' => 'El campo archivo .csv es obligatorio']], 422);
        }

        try {
            $importData = new SurveyUsersImport;
            Excel::import($importData, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json(['data' => ['message' => 'Existio un error al cargar el documento']], 422);
        }
        if (!$importData->getRowCount()) {
            return response()->json(['data' => ['message' => 'No existen registros en base de datos del listado cargado']], 400);
        }
        return response()->json(['data' => ['message' => 'Listado cargado con exito']], 200);
    }

    public function getUserRut()
    {
        return response()->json(['data' => UserRutList::get()]);
    }
}