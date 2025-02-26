<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Citas;
use App\Rules\TipoVacio;
use App\Models\TipoLavado;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JuegosController extends Controller
{

/*
* Método que nos lleva a la página de registrar juegos, solo si somos administradores
* Se llama desde el menú exclusivo para adminsitradores.
* 
*/

    public function create()
    {
        if (Auth::check() == false) {
            return redirect()->route('usuarios.login')->with('success', 'No tienes permiso, identifícate.');
        }

        if (Auth::user()->isAdmin != 1) {
            return redirect()->route('usuarios.login')->with('error', 'No tienes permiso, no eres administrador.');
        }

        return view('juegos.create');
    }

/*
* Método para mostrar el perfil individual de cada juego, mandándole tanto la información de dicho juego como los anuncios que lo usan
* Se llama cada vez que hacemos click en un enlace preparado para mostrar la información del juego
* 
*/    

    public function show($nombre)
    {
        $juego = DB::table('juegos')->where('nombre', $nombre)->first();
        if (!$juego) {
            return abort(404, 'Juego no encontrado');
        }

        $anuncios = DB::table('anuncio')
            ->where('juegocode', $juego->code)
            ->select('anuncio.*', DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas'))
            ->get();

        return view('juegos.juego', compact('juego', 'anuncios'));
    }

/*
* Método que usamos para registrar juegos.
* Se llama cuando en la página de adminsitradores de Crear juego rellenamos y mandamos el formulario
* 
*/

    public function upload(Request $request)
    {
        $request->validate([
            'tipo'   => 'required|in:coop,versus,versus/coop',
            'nombre' => [
                'required',
                'string',
                'unique:juegos,nombre',
                'regex:/^[A-Za-z0-9\s]+$/'
            ],
            'imagen' => 'required|image|mimes:jpg,jpeg,webp|max:2048'
        ]);

        $image = $request->file('imagen');
        $dimensions = getimagesize($image);
        if (!$dimensions) {
            return back()->withErrors(['imagen' => 'No se pudo obtener la información de la imagen.']);
        }
        $width  = $dimensions[0];
        $height = $dimensions[1];

        if ($width > 256 || $height > 256) {
            return back()->withErrors(['imagen' => 'El avatar no puede exceder de 256x256 píxeles.']);
        }

        $destinationPath = public_path('images/juegos');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $maxCode = DB::table('juegos')->max('code');
        $nextCode = $maxCode ? $maxCode + 1 : 1;
        $extension = $image->getClientOriginalExtension();
        $fileName = strtolower(str_replace(' ', '', $request->nombre)) . '.' . $extension;

        if (file_exists($destinationPath . '/' . $fileName)) {
            return back()->withErrors(['nombre' => 'Ya existe un archivo con ese nombre (error interno).']);
        }

        $image->move($destinationPath, $fileName);

        DB::table('juegos')->insert([
            'nombre'      => $request->nombre,
            'rutaimagen'  => $fileName,
            'tipo'        => $request->tipo,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->back()->with('success', 'Imagen subida correctamente.');
    }

/*
* Método que nos devuelve la lista de juegos que tenemos en la base de datos.
* Se llama desde el menú pñrincipal al hacer click en "Lista de juegos"
* 
*/

    public function listado()
    {
        $juegos = DB::table('juegos')->get();
        return view('juegos.listado', compact('juegos'));
    }
}
