<?php

namespace App\Http\Controllers;

use App\Models\conexion;
use App\Http\Requests\StoreconexionRequest;
use App\Http\Requests\UpdateconexionRequest;

class ConexionController extends Controller
{
    /**
     * Mostrar conexiones.
     */
    public function read()
    {
        if(isset($_GET["id"])){
            return conexion::find($_GET["id"]);
        } else {
            return conexion::all();
        }
    }

    /**
     * Crear conexion.
     */
    public function create(StoreconexionRequest $request)
    {
        conexion::create($request->all());
        
        return "conexion creada";
    }
    
    /**
     * Actualizar conexion.
     */
    public function update(UpdateconexionRequest $request, conexion $conexion)
    {
        conexion::findOrFail($conexion->id)->update($request->all());
        return "conexion actualizada";
    }

    /**
     * Eliminar conexion.
     */
    public function delete(conexion $conexion)
    {
        conexion::findOrFail($conexion->id)->delete();
        return "conexion eliminada";
    }
}
