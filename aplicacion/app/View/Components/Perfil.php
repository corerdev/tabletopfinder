<?php

namespace App\View\Components;

use App\Models\Usuarios;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Perfil extends Component
{
    public $nombre;
    public $log;
    
    public function __construct()
    {
        if (Auth::check())
        {
            $this->log = true;
            $usuario = Usuarios::find(Auth::id(),['uuid','username']);
            $this->nombre = $usuario->getAttributes()['username'];
        }
        else
        {
            $this->log = false;
        }
    }

    public function render()
    {
        return view('components.perfil');
    }
}