<?php

namespace App\Http\Controllers;

use App\Models\ruta;
use App\Http\Requests\StorerutaRequest;
use App\Http\Requests\UpdaterutaRequest;
use App\Models\conexion;
use App\Models\ubicacion;
use stdClass;

class RutaController extends Controller
{
    /**
     * Mostrar rutas.
     */
    public function read()
    {
        if (isset($_GET["id"])) {
            $ruta = ruta::find($_GET["id"]);
            $conexions = $ruta->conexiones()->get();

            $ubicaciones = [];
            $conexiones = [];

            foreach ($conexions as $conexion) {
                $ubicacion1 = ubicacion::find($conexion->nodo_a);
                $ubicacion2 = ubicacion::find($conexion->nodo_b);

                $c = new stdClass();
                $c->ubicacion1 = $ubicacion1;
                $c->ubicacion2 = $ubicacion2;
                $c->peso = $conexion->peso;

                $conexiones[] = $c;

                if (!in_array($ubicacion1, $ubicaciones, false)) {
                    $ubicaciones[] = $ubicacion1;
                }
                if (!in_array($ubicacion2, $ubicaciones, false)) {
                    $ubicaciones[] = $ubicacion2;
                }
            }

            $json = new stdClass();

            $json->ubicaciones = $ubicaciones;
            $json->conexiones = $conexiones;
            $json->inicio = ubicacion::find($ruta->nodo_inicial);

            return $json;
        } else {
            $rutas = [];

            $r = ruta::all();

            foreach ($r as $root) {
                $ruta = new stdClass();
                $ubicacion = ubicacion::find($root->nodo_inicial);

                $ruta->id = $root->id;
                $ruta->nodo_inicial = $ubicacion;

                $rutas[] = $ruta;
            }

            return $rutas;
        }
    }

    /**
     * Crear ruta.
     */
    public function create(StorerutaRequest $request)
    {
        $ruta = new ruta;
        $conexiones = $request->input("conexiones");
        $nodo_inicial = $request->input("nodo_inicial");

        $ubicaciones = [];
        
        foreach($conexiones as $c){
            $conexion = new conexion;

            $conexion->peso = $c->peso;

            $ubicacion1 = new ubicacion;
            $ubicacion2 = new ubicacion;

            $ubicacion1->nombre = $c->nodo_a->nombre;
            $ubicacion1->posicionx = $c->nodo_a->posicionx;
            $ubicacion1->posiciony = $c->nodo_a->posiciony;
            
            $ubicacion2->nombre = $c->nodo_a->nombre;
            $ubicacion2->posicionx = $c->nodo_a->posicionx;
            $ubicacion2->posiciony = $c->nodo_a->posiciony;

            if (!in_array($ubicacion1, $ubicaciones, false)) {
                $ubicacion1->save();
                $ubicaciones[] = $ubicacion1;
            }
            if (!in_array($ubicacion2, $ubicaciones, false)) {
                $ubicacion2->save();
                $ubicaciones[] = $ubicacion2;
            }

            
            $ruta->conexiones()->save($conexion);
        }
        
        $ruta->nodo_inicial = $nodo_inicial;
        
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
