<?php

namespace App\View\Components;

use App\Models\Juegos;
use Illuminate\View\Component;

class SelectJuego extends Component
{

    //Hacemos un select, parecido al que se nos proporcionaba de tipos de noticia, simplemente ligeramente adaptado

    public $listado;
    public $selectTipo;
    
   

    public function __construct($selectTipo)
    {
        $this->selectTipo =  $selectTipo;
        $this->listado = Juegos::orderByDesc('nombre')->get();
    }

    
    
    public function render()
    {
        return view('components.select-juego');
    }
}