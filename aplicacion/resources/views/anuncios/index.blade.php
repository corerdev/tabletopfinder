@extends("layouts.basico")

@section('title','Listado de citas')

@section('contenido')

<table class="tableAnuncios">
<tr>
<td><strong>Usuario</strong></td>
<td><strong>Titulo</strong></td>
<td><strong>Juego</strong></td>
<td><strong>Plazas</strong></td>

</tr>
@foreach($anuncios as $r)    
                    
<tr>

<td>
    {{$r->useruuid}}
 </td>
 <td>
    {{$r->titulo}}

</td>
<td>
    {{$r->juegocode}}
</td>
<td>
    {{$r->plazas}} - {{$r->plazasocupadas}}
</td>
</tr>
@endforeach
 </table>

@endsection