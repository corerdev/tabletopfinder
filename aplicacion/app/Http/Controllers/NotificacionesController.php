<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificacionesController extends Controller
{

/*
* Método que coge las notificaciones y los mensajes que tenemos como recipientes y los manda a la vista de notificaciones para mostrarnoslos.
* Se llama desde el menú desplegable del usuario
* 
*/

    public function notificaciones()
    {
        if (Auth::check() == false) {
            return redirect()->route('usuarios.login')->with('success', 'No tienes permiso, identifícate.');
        }

        $userUuid = auth()->user()->uuid;
        $notificaciones = DB::table('notificaciones')
            ->join('usuarios as u', 'notificaciones.solicitante', '=', 'u.uuid')
            ->join('anuncio as a', 'notificaciones.anuncio', '=', 'a.uuid')
            ->where('notificaciones.notificado', $userUuid)
            ->select(
                'notificaciones.id',
                'notificaciones.hora',
                'u.username as solicitante_username',
                'u.uuid as solicitante_uuid',
                'a.titulo as anuncio_titulo',
                'a.uuid as anuncio_uuid',
                'notificaciones.tipo'
            )
            ->orderBy('notificaciones.hora', 'desc')
            ->get();

        $mensajes = DB::table('notificaciones')
            ->join('usuarios as u', 'notificaciones.solicitante', '=', 'u.uuid')
            ->where('notificaciones.notificado', $userUuid)
            ->where('notificaciones.tipo', 'chat_aviso')
            ->select(
                'notificaciones.id',
                'notificaciones.hora',
                'u.username as solicitante_username',
                'u.uuid as solicitante_uuid',
                'notificaciones.tipo'
            )
            ->orderBy('notificaciones.hora', 'desc')
            ->get();

        return view('notificaciones.notificaciones', compact('notificaciones', 'mensajes'));
    }

/*
* Método que envía las notificaciones, registrandolas en su BD.
* 
* 
*/    

    public function store(Request $request)
    {
        $data = $request->validate([
            'notificado'  => 'required|uuid',
            'solicitante' => 'required|uuid',
            'anuncio'     => 'required|uuid',
            'tipo'        => 'required|string'
        ]);

        DB::table('notificaciones')->insert([
            'notificado'  => $data['notificado'],
            'solicitante' => $data['solicitante'],
            'anuncio'     => $data['anuncio'],
            'tipo'        => $data['tipo'],
            'hora'        => now()
        ]);

        return back()->with('success', 'Se ha enviado la notificación correctamente.');
    }

/*
* Método que borra al usuario de una partida en concreto, mandando la notificación al dueño de la partida.
* Se llama desde el método "Abandonar que le paarece a los usuarios en las partidas en las que están.
* 
*/    

    public function abandonar(Request $request)
    {
        $data = $request->validate([
            'notificado'  => 'required|uuid',
            'solicitante' => 'required|uuid',
            'anuncio'     => 'required|uuid',
            'tipo'        => 'required|string'
        ]);

        $deleted = DB::table('plazasjuegos')
            ->where('uuiduser', $data['solicitante'])
            ->where('uuidanuncio', $data['anuncio'])
            ->delete();

        if ($deleted) {
            DB::table('notificaciones')->insert([
                'notificado'  => $data['notificado'],
                'solicitante' => Auth::user()->uuid,
                'anuncio'     => $data['anuncio'],
                'tipo'        => 'abandono',
                'texto'       => null,
                'hora'        => now()
            ]);
            return back()->with('success', 'El jugador ha sido expulsado de la partida.');
        } else {
            return back()->with('error', 'No se encontró al jugador en la partida.');
        }
    }

/*
* Método que acepta al usuario solicitante en la partida que solicitó, registrando la notificación en la BD y al usuario junto al juego en plazasjuegos.
* Se llama desde la notificación que recibe el dueño de la partida cuando el solicitante hace la solicitud.
* 
*/    

    public function accept($id)
    {
        $notificacion = DB::table('notificaciones')->where('id', $id)->first();
        if (!$notificacion) {
            return back()->with('error', 'Notificación no encontrada.');
        }

        DB::table('plazasjuegos')->insert([
            'uuidanuncio' => $notificacion->anuncio,
            'uuiduser'    => $notificacion->solicitante
        ]);

        DB::table('notificaciones')->insert([
            'notificado'  => $notificacion->solicitante,
            'solicitante' => $notificacion->notificado,
            'anuncio'     => $notificacion->anuncio,
            'tipo'        => 'confirmación_unirse',
            'hora'        => now(),
            'texto'       => null
        ]);

        DB::table('notificaciones')->where('id', $id)->delete();
        return back()->with('success', 'La solicitud ha sido aceptada y el jugador se ha agregado a la partida.');
    }

/*
* Método que rechaza la petición de un usuario de unirse a una partida, mandándole una notificación de vuelta.
* Se llama desde la notificación que recibe el dueño de la partida cuando el solicitante hace la solicitud.
* 
*/    

    public function reject($id)
    {
        $notificacion = DB::table('notificaciones')->where('id', $id)->first();
        if (!$notificacion) {
            return back()->with('error', 'Notificación no encontrada.');
        }

        DB::table('notificaciones')->insert([
            'notificado'  => $notificacion->solicitante,
            'solicitante' => $notificacion->notificado,
            'anuncio'     => $notificacion->anuncio,
            'tipo'        => 'declinación_unirse',
            'hora'        => now(),
            'texto'       => null
        ]);

        DB::table('notificaciones')->where('id', $id)->delete();
        return back()->with('success', 'La solicitud ha sido rechazada.');
    }

/*
* Método que borra la petición de la que coge la id.
* Se llama desde la notificación que permite el acto de simplemente borrarla.
* 
*/     

    public function delete($id)
    {
        DB::table('notificaciones')->where('id', $id)->delete();
        return back()->with('success', 'La notificación ha sido borrada.');
    }

/*
* Método que cuenta las notificaciones que tiene un usuario.
* Se llama desde es script de la página base para saber si tiene que ser notificado con alertas.
* 
*/     

    public function contarNotificaciones()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $userUuid = Auth::user()->uuid;
        $count = DB::table('notificaciones')
            ->where('notificado', $userUuid)
            ->where('tipo', '!=', 'mensaje')
            ->count();

        return response()->json(['count' => $count]);
    }

/*
* Método que manda un mensaje de un usuario a otro, y comprueba si este tiene ya una notificación de mensaje entre ambos. Si no existe, la crea.
* Se llama desde la ventana de chat entre dos usuarios.
* 
*/     

    public function enviar(Request $request)
    {
        $request->validate([
            'para'      => 'required|exists:usuarios,uuid',
            'contenido' => 'required|string|max:500',
        ]);

        $notificado   = $request->para;
        $solicitante  = Auth::user()->uuid;
        $existeAviso  = DB::table('notificaciones')
            ->where('notificado', $notificado)
            ->where('solicitante', $solicitante)
            ->where('tipo', 'chat_aviso')
            ->exists();

        if (!$existeAviso) {
            DB::table('notificaciones')->insert([
                'notificado'  => $notificado,
                'solicitante' => $solicitante,
                'anuncio'     => 'ninguno',
                'tipo'        => 'chat_aviso',
                'hora'        => now(),
                'texto'       => 'Nuevo mensaje recibido.'
            ]);
        }

        DB::table('notificaciones')->insert([
            'notificado'  => $request->para,
            'solicitante' => Auth::user()->uuid,
            'anuncio'     => 'ninguno',
            'tipo'        => 'mensaje',
            'hora'        => now(),
            'texto'       => $request->contenido
        ]);

        return back()->with('success', 'Se ha enviado la notificación correctamente.');
    }

/*
* Método que coge los mensajes entre dos usuarios y los muestra como un chat.
* Se llama desde la vista de chat entre dos usuarios.
* 
*/     

    public function mostrarChat($contactoUuid)
    {
        if (!Auth::check()) {
            return redirect()->route('usuarios.login')->with('error', 'Debes iniciar sesión.');
        }

        $recipiente = DB::table('usuarios')->where('uuid', $contactoUuid)->first();
        $userUuid = auth()->user()->uuid;

        $mensajeAviso = DB::table('notificaciones')
            ->where('notificado', $userUuid)
            ->where('solicitante', $contactoUuid)
            ->where('tipo', 'chat_aviso')
            ->first();

        if ($mensajeAviso) {
            DB::table('notificaciones')->where('id', $mensajeAviso->id)->delete();
        }

        $mensajes = DB::table('notificaciones')
            ->join('usuarios as u', 'notificaciones.solicitante', '=', 'u.uuid')
            ->where('notificaciones.tipo', 'mensaje')
            ->where(function ($query) use ($userUuid, $contactoUuid) {
                $query->where(function ($q) use ($userUuid, $contactoUuid) {
                    $q->where('notificaciones.solicitante', $userUuid)
                      ->where('notificaciones.notificado', $contactoUuid);
                })
                ->orWhere(function ($q) use ($userUuid, $contactoUuid) {
                    $q->where('notificaciones.solicitante', $contactoUuid)
                      ->where('notificaciones.notificado', $userUuid);
                });
            })
            ->select(
                'notificaciones.id',
                'notificaciones.hora',
                'u.username as solicitante_username',
                'u.uuid as solicitante_uuid',
                'notificaciones.texto'
            )
            ->orderBy('notificaciones.hora', 'asc')
            ->get();

        return view('notificaciones.chat', compact('mensajes', 'contactoUuid', 'recipiente'));
    }

/*
* Método que elimina a un jugador de una partida y le manda la notificación de lo ocurrido.
* Se llama desde el boton "Expulsar" en la vista individual de cada anuncio, solo visto por el creador del anuncio.
* 
*/     

    public function deleteUserFromGame(Request $request)
    {
        $data = $request->validate([
            'user_uuid'    => 'required|uuid',
            'anuncio_uuid' => 'required|uuid',
        ]);

        $deleted = DB::table('plazasjuegos')
            ->where('uuiduser', $data['user_uuid'])
            ->where('uuidanuncio', $data['anuncio_uuid'])
            ->delete();

        if ($deleted) {
            DB::table('notificaciones')->insert([
                'notificado'  => $data['user_uuid'],
                'solicitante' => Auth::user()->uuid,
                'anuncio'     => $data['anuncio_uuid'],
                'tipo'        => 'expulsion',
                'texto'       => null,
                'hora'        => now()
            ]);
            return back()->with('success', 'El jugador ha sido expulsado de la partida.');
        } else {
            return back()->with('error', 'No se encontró al jugador en la partida.');
        }
    }
}
