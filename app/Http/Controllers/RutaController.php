<?php

namespace App\Http\Controllers;

use App\Models\ruta;
use App\Http\Requests\StorerutaRequest;
use App\Http\Requests\UpdaterutaRequest;

class RutaController extends Controller
{
    /**
     * Mostrar rutas.
     */
    public function read()
    {
        if(isset($_GET["id"])){
            return ruta::find($_GET["id"]);
        } else {
            return ruta::all();
        }
    }

    /**
     * Crear ruta.
     */
    public function create(StorerutaRequest $request)
    {
        ruta::create($request->all());

        return "ruta creada";
    }
    
    /**
     * Actualizar ruta.
     */
    public function update(UpdaterutaRequest $request, ruta $ruta)
    {
        ruta::findOrFail($ruta->id)->update($request->all());
        return "ruta actualizada";
    }

    /**
     * Eliminar ruta.
     */
    public function delete(ruta $ruta)
    {
        ruta::findOrFail($ruta->id)->delete();
        return "ruta eliminada";
    }
}
