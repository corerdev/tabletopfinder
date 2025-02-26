<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Juegos;
use App\Models\Anuncios;
use App\Rules\TipoVacio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnunciosController extends Controller
{
/*
* Método que nos manda a la landing page.
*
* 
*/

    public function landing()
    {
        return view('anuncios.landing');
    }

    //-----------------------------------------------------------------------
    //---                  CREACION DE PARTIDAS                 -------------
    //-----------------------------------------------------------------------

/*
* Método que nos lleva a la página de creación de partidas.
* Se llama desde el menú al querer ir a "Crear partida"
* 
*/

    public function create()
    {
        return view('anuncios.create');
    }

/*
* Método que crea las partidas. Recibe el request de anuncios.create, obteniendolo todo
* de un formulario. creando la partida y llevándonos a su perfil
* Se llama cuando en anuncios.create mandamos el formulario.
*/

    public function store(Request $request)
    {   
        $datos = $request->validate([
            'titulo'      => 'required|min:5|max:35|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]+$/',
            'descripcion' => 'required|min:5|max:500|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s()]+$/',
            'desccorta'   => 'required|min:5|max:30|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s()]+$/',
            'fondo'       => 'required|min:2|max:10|regex:/^[A-Za-z]+$/',
            'medio'       => 'required|max:10|regex:/^[A-Za-z]+$/',
            'juegocode'   => 'required|int',
            'plazas'      => 'required|max:2'
        ]);

        $uuidUser = auth()->user()->uuid;
        $datos['useruuid'] = $uuidUser;
        $datos['plazasocupadas'] = 0;

        $anuncio = Anuncios::create($datos);

        return redirect()->route('anuncios.show', $anuncio->uuid);
    }

    //-----------------------------------------------------------------------
    //---                  FUNCION DATATABLE                    -------------
    //-----------------------------------------------------------------------

/*
* Método que nos devuelve la vista de buscar partidas, mandándole el listado de partidas de la DB.
* Se llama desde el menú para acceder a la vista de las partidas.
* 
*/

    public function buscarPartidas()
    {
        if (Auth::check() == false) {
            return redirect()
                ->route('usuarios.login')
                ->with('success', 'No tienes permiso, identifícate.');
        }

        $listado = Juegos::orderByDesc('nombre')->get();

        return view('anuncios.buscarPartidas', compact('listado'));
    }

/*
* Método que manda las partidas a la datable a través de AJAX con el formato que le pedimos.
* Se usa a través de la datatable de buscarPartida
* 
*/

    public function getListado(Request $request)
    {
        $where = 'WHERE 1=1';

        if (!empty($request->search['value'])) {
            $where .= ' AND (';
            $stringAdded = false;
            foreach ($request->columns as $col) {
                $searchable = json_decode($col['searchable']);
                if ($searchable) {
                    if ($stringAdded) {
                        $where .= ' OR ';
                    }
                    $where .= $col['name'] . " LIKE '%" . $request->search['value'] . "%'";
                    $stringAdded = true;
                }
            }
            $where .= ')';
        }

        if (!empty($request->juego)) {
            $where .= " AND j.nombre = '" . $request->juego . "'";
        }

        if (!empty($request->tipoPartida)) {
            $where .= " AND a.medio = '" . $request->tipoPartida . "'";
        }

        if (isset($request->esTienda) && $request->esTienda === "0") {
            $where .= " AND u.esTienda = 0";
        }
        if (isset($request->esTienda) && $request->esTienda === "1") {
            $where .= " AND u.esTienda = 1";
        }

        $orderBy = 'ORDER BY ' . $request->columns[$request->order[0]['column']]['name'] . ' ' . $request->order[0]['dir'];
        $paginacion = ($request->length != -1) ? 'LIMIT ' . $request->length . ' OFFSET ' . $request->start : '';

// Este bloque nos manda los anuncios que le pedimos desde la datatable con el conteo de jugadores por partida en tiempo real, así como
// el nombre de los juegos y los jugadores

        $anuncios = DB::select(
            'SELECT a.*, j.nombre as juegonombre, u.username as usuarionombre
             FROM anuncio a 
             LEFT JOIN juegos j ON a.juegocode = j.code 
             LEFT JOIN usuarios u ON a.useruuid = u.uuid ' . 
             $where . 
             ' AND a.plazas <> (SELECT COUNT(*) FROM plazasjuegos p WHERE p.uuidanuncio = a.uuid) ' .
             $orderBy . ' ' . $paginacion
        );

        $recordsFiltered = DB::select(
            'SELECT COUNT(a.uuid) as recordsNum 
             FROM anuncio a 
             LEFT JOIN juegos j ON a.juegocode = j.code 
             LEFT JOIN usuarios u ON a.useruuid = u.uuid ' .
             $where .
             " AND a.plazas <> (SELECT COUNT(*) FROM plazasjuegos p WHERE p.uuidanuncio = a.uuid)"
        )[0]->recordsNum;

        $recordsTotal = DB::select('SELECT COUNT(uuid) as recordsNum FROM anuncio')[0]->recordsNum;

        $datos = [];

        foreach ($anuncios as $anuncio) {
            $playerCount = DB::table('plazasjuegos')
                             ->where('uuidanuncio', $anuncio->uuid)
                             ->count();

            $datos[] = [
                'uuid'           => $anuncio->uuid,
                'useruuid'       => $anuncio->useruuid,
                'descripcion'    => $anuncio->descripcion,
                'titulo'         => $anuncio->titulo,
                'juegocode'      => $anuncio->juegonombre,
                'plazas'         => $anuncio->plazas,
                'plazasocupadas' => $anuncio->plazasocupadas,
                'creador'        => $anuncio->usuarionombre,
                'playerCount'    => $playerCount,
            ];
        }

        return response()->json([
            'draw'            => $request->draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $datos
        ]);
    }

    //-----------------------------------------------------------------------
    //---                  PERFIL ANUNCIO                       -------------
    //-----------------------------------------------------------------------

/*
* Método que llama a lavista del perfil de cada anuncio. Recibe la UUID del anuncil, desde la cual buscamos el anuncio y con sus datos
* cogemos varios datos mas, como el nombre del juego, los jugadores que participan o los avatares y el fondo del propio anuncio.
* Se llama cada vez que hacemos click en un enlace que hayamos preparado para que se vea el anuncio
*/

    public function show($uuid)
    {
        if (Auth::check() == false) {
            return redirect()
                ->route('usuarios.login')
                ->with('success', 'No tienes permiso, identifícate.');
        }

        $anuncio = DB::table('anuncio')
            ->select(
                'anuncio.*',
                DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas')
            )
            ->where('uuid', $uuid)
            ->first();

        if (!$anuncio) {
            return abort(404, 'Anuncio no encontrado');
        }

        $nombreJuego = DB::table('anuncio')
                        ->join('juegos', 'anuncio.juegocode', '=', 'juegos.code')
                        ->select('juegos.nombre')
                        ->where('anuncio.uuid', $uuid)
                        ->get();

        $fondo = DB::table('fondos')->where('nombre', $anuncio->fondo)->first();
        $jugadores = DB::table('plazasjuegos')->where('uuidanuncio', $uuid)->get();
        $jugadoresIds = $jugadores->pluck('uuiduser');

        $usuarios = DB::table('usuarios')->whereIn('uuid', $jugadoresIds)->get();

        $creador = DB::table('usuarios')->where('uuid', $anuncio->useruuid)->first();

        $avatarNames = $usuarios->pluck('avatar')->unique();
        if ($creador && !$avatarNames->contains($creador->avatar)) {
            $avatarNames->push($creador->avatar);
        }

        $fondosAvatar = DB::table('fondos')
                        ->whereIn('nombre', $avatarNames)
                        ->get()
                        ->keyBy('nombre');

// Estos tres campos son necesarios para saber que le mostramos al usuario

        $usuarioYaInscrito = DB::table('plazasjuegos')
                                ->where('uuidanuncio', $uuid)
                                ->where('uuiduser', auth()->user()->uuid)
                                ->exists();

        $usuarioYaSolicitante = DB::table('notificaciones')
                                ->where('anuncio', $uuid)
                                ->where('solicitante', auth()->user()->uuid)
                                ->exists();

        $usuarioEsCreador = ($anuncio->useruuid == auth()->user()->uuid);

        return view('anuncios.perfil', compact(
            'anuncio',
            'fondo',
            'usuarios',
            'fondosAvatar',
            'usuarioYaInscrito',
            'usuarioEsCreador',
            'usuarioYaSolicitante',
            'nombreJuego',
            'creador'
        ));
    }

/*
* Método que elimina el anuncio que coincida con la UUID que le pasamos.
* Se llama desde el botón "Borrar partida" disponible para los creadores de cada partida en el perfil de la misma
* 
*/

    public function destroy($uuid)
    {
        $anuncio = Anuncios::where('uuid', $uuid)->firstOrFail();

        if ($anuncio->useruuid !== auth()->user()->uuid) {
            return redirect()
                ->back()
                ->with('error', 'No tienes permiso para borrar este anuncio.');
        }

        $anuncio->delete();

        return redirect()
            ->route('anuncios.landing')
            ->with('success', 'La partida ha sido borrada.');
    }
}
