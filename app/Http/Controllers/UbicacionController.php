<?php

namespace App\Http\Controllers;

use App\Models\ubicacion;
use App\Http\Requests\StoreubicacionRequest;
use App\Http\Requests\UpdateubicacionRequest;

class UbicacionController extends Controller
{
    /**
     * Mostrar ubicaciones.
     */
    public function read()
    {
        if(isset($_GET["id"])){
            return ubicacion::find($_GET["id"]);
        } else {
            return ubicacion::all();
        }
    }

    /**
     * Crear ubicacion.
     */
    public function create(StoreubicacionRequest $request)
    {
        ubicacion::create($request->all());

        return "ubicacion creada";
    }
    
    /**
     * Actualizar ubicacion.
     */
    public function update(UpdateubicacionRequest $request, ubicacion $ubicacion)
    {
        ubicacion::findOrFail($ubicacion->id)->update($request->all());
        return "ubicacion actualizada";
    }

    /**
     * Eliminar ubicacion.
     */
    public function delete(ubicacion $ubicacion)
    {
        ubicacion::findOrFail($ubicacion->id)->delete();
        return "ubicacion eliminada";
    }
}
