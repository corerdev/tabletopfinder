@extends('layouts.basico')

@section('title', 'Buscar Partidas')

@section('scripts')
<script>
$(document).ready(function() {
    function inicializarDataTable(selector, esTienda) {
        var pageLength = esTienda == 0 ? 10 : 20;
        return $(selector).DataTable({
            "paging": true,
            "pagingType": "numbers",
            "lengthChange": false,
            "searching": false,
            "pageLength": pageLength,
            "info": false,
            "order": [[1, "asc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('anuncios.getListado') }}",
                "type": "POST",
                "data": function(d) {
                    d.juego = $('#juego').val();
                    d.tipoPartida = $('input[name=tipoPartida]:checked').val();
                    d._token = "{{ csrf_token() }}";
                    d.esTienda = esTienda;
                    console.log(d);
                }
            },
            "columns": [
                {
                    data: 'uuid',
                    name: 'uuid',
                    orderable: false,
                    searchable: false,
                    visible: false
                },
                {
                    data: 'titulo',
                    name: 'titulo',
                    render: function(data, type, row) {
                        return '<a href="/tabletopFinder/aplicacion/public/anuncios/' + encodeURIComponent(row.uuid) + '">' + data + '</a>';
                    }
                },
                {
                    data: 'creador',
                    name: 'creador',
                    orderable: false,
                    searchable: false,
                    className: 'ocultar-mobile',
                    render: function(data, type, row) {
                        return '<a href="/tabletopFinder/aplicacion/public/usuarios/' + encodeURIComponent(row.creador) + '">' + data + '</a>';
                    }
                },
                {
                    data: 'descripcion',
                    name: 'descripcion',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'juegocode',
                    name: 'juegocode',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<a href="/tabletopFinder/aplicacion/public/juegos/' + encodeURIComponent(row.juegocode) + '">' + data + '</a>';
                    }
                },
                {
                    data: 'plazas',
                    name: 'plazas',
                    orderable: false,
                    searchable: false,
                    className: 'ocultar-mobile',
                    render: function(data, type, row) {
                        return row.playerCount + ' / ' + data;
                    }
                }
            ],
            language: {
                "decimal":        ",",
                "emptyTable":     "No hay datos en la tabla",
                "info":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty":      "",
                "infoFiltered":   "",
                "thousands":      ".",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search":         "Buscar:",
                "zeroRecords":    "No han encontrado registros",
                "paginate": {
                    "first":      "Primero",
                    "last":       "Último",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "aria": {
                    "sortAscending":  ": Click/return para ordenar ascendentemente",
                    "sortDescending": ": Click/return para ordenar descendentemente"
                }
            }
        });
    }

    // Inicializar DataTables para jugadores (esTienda = 0) y tiendas (esTienda = 1)
    var tableJugadores = inicializarDataTable('#listAnuncios', 0);
    var tableTiendas = inicializarDataTable('#listAnunciosTienda', 1);

    // Al enviar el formulario, evitamos el submit tradicional y recargamos ambas tablas con los nuevos filtros.
    $('#filtroPartida').on('submit', function(e) {
        e.preventDefault();
        tableJugadores.ajax.reload();
        tableTiendas.ajax.reload();
    });
});

</script>
@endsection

@section('contenido')
<div class="filtro-container">
  <h2 class="filtro-titulo">Buscar Partidas</h2>

  <form id="filtroPartida" class="filtro-form">
    <div class="form-group">
      <label for="juego">Juego</label>
      <select name="juego" id="juego" class="filtro-select">
        <option value="">-</option>
        @foreach ($listado as $op)
          <option value="{{ $op->nombre }}">
            {{ $op->nombre }}
          </option>
        @endforeach
      </select>
      @error('juegos')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
      @enderror
    </div>
    
    <div class="form-group filtro-radio-group">
      <label>Tipo de partida:</label>
      <div class="radio-options">
        <input type="radio" name="tipoPartida" value="online" id="online">
        <label for="online">Online</label>
        <input type="radio" name="tipoPartida" value="fisico" id="fisico">
        <label for="fisica">Física</label>
      </div>
    </div>
    
    <button type="submit" class="filtro-btn">Buscar</button>
  </form>
</div>

<div>
    <h2 class="buscarPartidas-titulo">Partidas de nuestras tiendas</h2>
    <table id="listAnunciosTienda">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Creador</th>
                <th>Descripción</th>
                <th>Juego</th>
                <th>Plazas</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>   
</div>

<div>
    <h2 class="buscarPartidas-titulo">Partidas de nuestros jugadores</h2>
    <table id="listAnuncios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Creador</th>
                <th>Descripción</th>
                <th>Juego</th>
                <th>Plazas</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>   
</div>
@endsection