<?php

namespace App\View\Components;

use App\Models\Fondos;
use Illuminate\View\Component;

class SelectFondo extends Component
{

    public $listado;
    public $selectTipo;
    
   

    public function __construct($selectTipo)
    {
        $this->selectTipo =  $selectTipo;
        $this->listado = Fondos::where('tipo', 'fondo')->orderByDesc('nombre')->get();
    }

    
    
    public function render()
    {
        return view('components.select-fondo');
    }
}