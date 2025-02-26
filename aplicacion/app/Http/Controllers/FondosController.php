<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Rules\TipoVacio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Fondos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FondosController extends Controller
{
/*
* Muestra la página de administrador para subir fondos, solo si somos adminsitrador.
* Se llama desde el menú exclusivo para administradores.
* 
*/
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('usuarios.login')
                             ->with('success', 'No tienes permiso, identifícate.');
        }

        if (Auth::user()->isAdmin != 1) {
            return redirect()->route('usuarios.login')
                             ->with('error', 'No tienes permiso, no eres administrador.');
        }

        return view('fondos.upload');
    }

/*
* Procesa la subida de imágenes para fondos y avatares, asegurándose de que los datos y dimensiones son correctas, 
* insertándolo en la tabla y guardando el archivo con el nombre correcto
* Se llama cuando en la página de administrador para registrar fondos mandamos el formulario.
*/
    public function upload(Request $request)
    {
        $request->validate([
            'tipo'   => 'required|in:avatar,fondo',
            'nombre' => [
                'required',
                'string',
                'unique:fondos,nombre',
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

        if ($request->tipo === 'avatar') {
            if ($width > 256 || $height > 256) {
                return back()->withErrors(['imagen' => 'El avatar no puede exceder de 256x256 píxeles.']);
            }
        } elseif ($request->tipo === 'fondo') {
            if ($width > 1000 || $height > 450) {
                return back()->withErrors(['imagen' => 'El fondo no puede exceder de 1000x450 píxeles.']);
            }
        }

        $destinationPath = public_path('images/fondos');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $maxCode = DB::table('fondos')->max('code');
        $nextCode = $maxCode ? $maxCode + 1 : 1;

        $extension = $image->getClientOriginalExtension();
        $fileName  = 'imagen' . $nextCode . '.' . $extension;

        if (file_exists($destinationPath . '/' . $fileName)) {
            return back()->withErrors(['nombre' => 'Ya existe un archivo con ese nombre (error interno).']);
        }

        $image->move($destinationPath, $fileName);

        DB::table('fondos')->insert([
            'nombre' => $request->nombre,
            'ruta'   => 'images/fondos/' . $fileName,
            'tipo'   => $request->tipo
        ]);

        return redirect()->back()->with('success', 'Imagen subida correctamente.');
    }
}
