<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UsuariosController extends Controller
{

/*
* Método que nos lleva al login.
* Se llama desde el menú principal.
* 
*/     

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('anuncios.landing');
        }
        return view('usuarios.login');
    }

/*
* Método que llama a la vista donde registramos a los usuarios.
* Se llama desde el login con el botón "Usuario nuevo"
* 
*/     

    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('anuncios.landing');
        }
        return view('usuarios.create');
    }

/*
* Método que nos logea con nuestros datos en el sistema.
* Se llama desde el botón de "login" en el login si nuestros datos son correctos.
* 
*/     

    public function authenticate(Request $request)
    {
        $datos = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($datos)) {
            $request->session()->regenerate();
            return redirect()->route('anuncios.landing');
        }
        return back()->withErrors([
            'username' => 'El usuario y la contraseña no son correctos',
        ]);
    }

/*
* Método que nos registra un usuario con los datos que le pasamos.
* Se llama desde el formulario de usuarios.create
* 
*/     

    public function createUser(Request $request)
    {
        $datos = $request->validate([
            'username' => 'required|min:3|max:15|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'required|max:40|regex:/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/',
            'passwordRepetir' => 'required|min:8|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/',
            'password' => 'required|min:8|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/'
        ]);

        $resultsEmail = DB::table('usuarios')->where('email', $datos['email'])->get();
        $resultsUsername = DB::table('usuarios')->where('username', $datos['username'])->get();

        if (!$resultsEmail->isEmpty()) {
            return back()->withErrors([
                'email' => 'El email ya está registrado en la base de datos',
            ]);
        }
        if (!$resultsUsername->isEmpty()) {
            return back()->withErrors([
                'username' => 'El nombre de usuario ya está registrado en la base de datos',
            ]);
        }
        if ($datos['password'] != $datos['passwordRepetir']) {
            return back()->withErrors([
                'passwordRepetida' => 'Las contraseñas no coinciden.',
            ]);
        }
        if ($datos['password'] == $datos['passwordRepetir']) {
            $datos['descripcion'] = '¡Hola! Soy un usuario nuevo y aún no he cambiado mi descripción.';
            $datos['avatar'] = 'basicazul';
            $datos['isAdmin'] = 0;
            $datos['google_id'] = null;
            $datos['descTienda'] = null;
            $datos['telfTienda'] = null;
            $datos['dirTienda'] = null;
            $datos['emailTienda'] = null;
            $datos['esTienda'] = 0;
            $passwordSegura = Hash::make($datos['password']);
            $datos['password'] = $passwordSegura;

            Usuarios::create($datos);

            return redirect()->route('usuarios.login')->with('success', 'El usuario se ha creado correctamente.');
        }
    }

/*
* Método que nos muestra el perfil de un usuario, cogiendo los datos del usuario, de las partidas en las que está, así como datos de avatar
* y personas con las que tiene chats activos, si se accede al perfil propio.
* Se llama desde cualquier link que esté preparado con el nombre del usuario para llevar a su perfil.
* Además, está preparado para "actuar" como miPerfil, en caso de que un usuario intente acceder a su propio show
*/     

    public function show($nombre)
    {
        if (Auth::check() == false) {
            return redirect()->route('usuarios.login')->with('success', 'No tienes permiso, identifícate.');
        }

        $usuario = DB::table('usuarios')->where('username', $nombre)->first();
        if (!$usuario) {
            return abort(404, 'Usuario no encontrado');
        }

        $anuncios = DB::table('anuncio')
            ->join('juegos', 'anuncio.juegocode', '=', 'juegos.code')
            ->select(
                'anuncio.*',
                'juegos.nombre as nombre_juego',
                DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas')
            )
            ->where('anuncio.useruuid', $usuario->uuid)
            ->get();

        $rutaObj = DB::table('fondos')->where('nombre', $usuario->avatar)->first();
        $ruta = $rutaObj->ruta;

        if (Auth::check() && Auth::user()->uuid === $usuario->uuid) {
            $uuid = Auth::user()->uuid;
            $chats = DB::table('notificaciones')
                ->where('tipo', 'mensaje')
                ->where(function ($query) use ($uuid) {
                    $query->where('notificado', $uuid)
                        ->orWhere('solicitante', $uuid);
                })
                ->join('usuarios as u', function ($join) use ($uuid) {
                    $join->on('notificaciones.solicitante', '=', 'u.uuid')
                        ->orOn('notificaciones.notificado', '=', 'u.uuid');
                })
                ->where('u.uuid', '!=', $uuid)
                ->select('u.uuid', 'u.username', 'u.avatar')
                ->distinct()
                ->get();

            $avatarNames = $chats->pluck('avatar')->unique();

            $fondosAvatar = DB::table('fondos')
                ->whereIn('nombre', $avatarNames)
                ->get()
                ->keyBy('nombre');

            $participa = DB::table('plazasjuegos')
                ->join('anuncio', 'plazasjuegos.uuidanuncio', '=', 'anuncio.uuid')
                ->join('juegos', 'anuncio.juegocode', '=', 'juegos.code')
                ->select(
                    'anuncio.*',
                    'juegos.nombre as nombre_juego',
                    DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas')
                )
                ->where('plazasjuegos.uuiduser', $usuario->uuid)
                ->get();

            return view('usuarios.miPerfil', compact('usuario', 'anuncios', 'ruta', 'chats', 'fondosAvatar', 'participa'));
        }

        return view('usuarios.perfil', compact('usuario', 'anuncios', 'ruta'));
    }

/*
* Método que muestra la página de miPerfil, con los datos del usuario, de sus partidas, de chats, y de avatares.
* Se llama desde la sección "mi perfil" en el menú principal.
* 
*/     

    public function miPerfil()
    {
        if (Auth::check() == false) {
            return redirect()->route('usuarios.login')->with('success', 'No tienes permiso, identifícate.');
        }

        $uuid = Auth::user()->uuid;
        $usuario = DB::table('usuarios')->where('uuid', $uuid)->first();
        if (!$usuario) {
            return abort(404, 'Usuario no encontrado');
        }

        $anuncios = DB::table('anuncio')
            ->join('juegos', 'anuncio.juegocode', '=', 'juegos.code')
            ->select(
                'anuncio.*',
                'juegos.nombre as nombre_juego',
                DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas')
            )
            ->where('anuncio.useruuid', $usuario->uuid)
            ->get();

        $participa = DB::table('plazasjuegos')
            ->join('anuncio', 'plazasjuegos.uuidanuncio', '=', 'anuncio.uuid')
            ->join('juegos', 'anuncio.juegocode', '=', 'juegos.code')
            ->select(
                'anuncio.*',
                'juegos.nombre as nombre_juego',
                DB::raw('(SELECT COUNT(*) FROM plazasjuegos WHERE plazasjuegos.uuidanuncio = anuncio.uuid) as plazas_ocupadas')
            )
            ->where('plazasjuegos.uuiduser', $usuario->uuid)
            ->get();

        $rutaObj = DB::table('fondos')->where('nombre', $usuario->avatar)->first();
        $ruta = $rutaObj->ruta;

        $chats = DB::table('notificaciones')
            ->where('tipo', 'mensaje')
            ->where(function ($query) use ($uuid) {
                $query->where('notificado', $uuid)
                      ->orWhere('solicitante', $uuid);
            })
            ->join('usuarios as u', function ($join) use ($uuid) {
                $join->on('notificaciones.solicitante', '=', 'u.uuid')
                     ->orOn('notificaciones.notificado', '=', 'u.uuid');
            })
            ->where('u.uuid', '!=', $uuid)
            ->select('u.uuid', 'u.username', 'u.avatar')
            ->distinct()
            ->get();

        $avatarNames = $chats->pluck('avatar')->unique();

        $fondosAvatar = DB::table('fondos')
            ->whereIn('nombre', $avatarNames)
            ->get()
            ->keyBy('nombre');

        return view('usuarios.miPerfil', compact('usuario', 'anuncios', 'ruta', 'chats', 'fondosAvatar', 'participa'));
    }

/*
* Método que llama la vista de edición de descripciones de perfil.
* Llamado desde el botón correspondiente en el miPerfil de cada usuario
* 
*/     

    public function editDesc($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.editDesc', compact('usuario'));
    }

/*
* Método que updatea la descripción del usuario.
* Se llama desde el botón de formulario en la pafina usuarios.editDesc
* 
*/     

    public function updateDesc(Request $request, $id)
    {
        $user = Usuarios::findOrFail($id);
        $validatedData = $request->validate([
            'descripcion' => 'required|min:5|max:500|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s()]+$/'
        ]);
        $user->update($validatedData);
        return redirect()->route('usuarios.miPerfil')->with('success', 'Usuario actualizado exitosamente.');
    }

/*
* Método que llama a la vista de edición de mail del usuario
* Se llama desde el botón correspondiente en la sección miPerfil de cada usuario
* 
*/     

    public function editEmail($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.editEmail', compact('usuario'));
    }

/*
* Método que updatea el email del usuario.
* Se llama desde el formulario de usuarios.editEmail
* 
*/     

    public function updateEmail(Request $request, $id)
    {
        $user = Usuarios::findOrFail($id);
        $validatedData = $request->validate([
            'email' => 'required|max:40|regex:/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/'
        ]);
        $user->update($validatedData);
        return redirect()->route('usuarios.miPerfil')->with('success', 'Usuario actualizado exitosamente.');
    }

/*
* Método que llama a la vista de edición de nombre de usuario
* Se llama desde el botón correspondiente en miPerfil
* 
*/     

    public function editUser($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.editUser', compact('usuario'));
    }

/*
* Método que updatea el username del usuario, rellenando una columna de date en la bd al hacerlo. También se comprueba si esa columna tiene fecha, y si es inferior a un mes;
* si lo es, se lanza un error, ya que el usuario solo puede cambiar de username una vez al mes.
* Se llamad esde el formulatio en usuarios.editUser
*/ 

    public function updateUser(Request $request, $id)
    {
        $user = Usuarios::findOrFail($id);
        $datos = $request->validate([
            'username' => 'required|min:3|max:15|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' . $user->id,
        ]);
        $resultsUsername = DB::table('usuarios')->where('username', $datos['username'])->get();
        if (!$resultsUsername->isEmpty()) {
            return back()->withErrors([
                'username' => 'El nombre de usuario ya está registrado en la base de datos',
            ]);
        }
        if ($user->ultimaUpdate && Carbon::now()->lessThan(Carbon::parse($user->ultimaUpdate)->addMonth())) {
            return redirect()->back()->withErrors([
                'username' => 'Debe transcurrir al menos un mes desde la última actualización del username.'
            ]);
        }
        $datos['ultimaUpdate'] = Carbon::now();
        $user->update($datos);
        return redirect()->route('usuarios.miPerfil')->with('success', 'Usuario actualizado exitosamente.');
    }

/*
* Método que llama a la vista de selección de avatares.
* Se llama desde el botón de editar avatar en miPerfil
* 
*/     

    public function editAvatar($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $avatares = DB::table('fondos')->where('tipo', 'avatar')->get();
        return view('usuarios.editAvatar', compact('usuario', 'avatares'));
    }

/*
* Método que updatea el avatar del usuario con el escogido en la página de selección de avatares.
* Se llama desde el formulario que cada avatar tiene en dicha página
* 
*/ 

    public function updateAvatar(Request $request, $id)
    {
        $user = Usuarios::findOrFail($id);
        $datos = $request->validate([
            'avatar' => 'required|min:3|max:10|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ]);
        $datosUser['avatar'] = $datos['avatar'];
        $user->update($datosUser);
        return redirect()->route('usuarios.miPerfil')->with('success', 'Usuario actualizado exitosamente.');
    }

/*
* Método para convertir en tienda a un usuario, poniendo su esTienda a 1 y inicializando sus parámetros de tienda con dummys.
* Se llama desde el panel de adminsitrador
* 
*/     

    public function makeTienda(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,uuid'
        ]);
        $user = Usuarios::where('uuid', $request->user_id)->first();
        if (!$user) {
            return redirect()->back()->withErrors('Usuario no encontrado.');
        }
        $user->esTienda = 1;
        $user->dirTienda = 'Aquí va la dirección de tu tienda';
        $user->descTienda = 'Aquí va la descripción de tu tienda';
        $user->emailTienda = 'emaildetutienda@tienda.com';
        $user->telfTienda = '000000000';
        $user->save();
        return redirect()->back()->with('success', 'El usuario ha sido convertido en tienda correctamente.');
    }

/*
* Método para editar los datos de la tienda que cada usuario tienda puede usar
* Se llama desde el botón de "editar tienda" en el miPerfil de los usuarios tienda
* 
*/     

    public function editTienda($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.editTienda', compact('usuario'));
    }

/*
* Método que updatea los campos de tienda de un usuario tienda.
* Se llama desde el botón de formulario de usuarios.editTienda
* 
*/     

    public function updateTienda(Request $request, $id)
    {
        $user = Usuarios::findOrFail($id);
        $datos = $request->validate([
            'descTienda'  => 'required|min:1|max:500|regex:/^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s\.,:;()\-!?¿¡]+$/',
            'telfTienda'  => 'required|regex:/^[0-9]{9}$/',
            'dirTienda'   => 'required|min:1|max:50|regex:/^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s\.,ºª\-]+$/',
            'emailTienda' => 'required|min:5|max:50|regex:/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/i'
        ]);
        $user->update($datos);
        return redirect()->route('usuarios.miPerfil')->with('success', 'Usuario actualizado exitosamente.');
    }

/*
* Método para cerrar sesión.
* Se llama desde el botón logout en el menú principal
* 
*/     

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('usuarios.login');
    }

/*
* Método que nos lleva a la página de adminsitración si somos adminsitradores
* Se llama desde el menú contextual de adminsitrador
* 
*/     

    public function admin()
    {
        if (Auth::check() == false) {
            return redirect()->route('usuarios.login')->with('success', 'No tienes permiso, identifícate.');
        }
        if (Auth::user()->isAdmin != 1) {
            return redirect()->route('usuarios.login')->with('error', 'No tienes permiso, no eres administrador.');
        }
        $usuariosRegulares = Usuarios::where('esTienda', 0)->get();
        $usuarios = Usuarios::all();
        return view('usuarios.adminView', compact('usuarios', 'usuariosRegulares'));
    }

/*
* Método para eliminar un usuario usado por adminsitradores
* Se llama desde el botón de formulario en la sección de usuarios.adminView
* 
*/     

    public function banHammer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,uuid'
        ]);
        $user = Usuarios::where('uuid', $request->user_id)->first();
        if (!$user) {
            return redirect()->back()->withErrors('Usuario no encontrado.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'El usuario ha sido eliminado correctamente.');
    }
}
