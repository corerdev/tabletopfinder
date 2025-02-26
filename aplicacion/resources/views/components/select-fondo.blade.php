    <div class="form-group">
        <label for="body">Fondo del anuncio</label>
        <small class="crearanuncio-help">Fondo que aparecerá al abrir el anuncio. Debes seleccionar uno para crear el anuncio</small>
        <select id ="fondo" name="fondo" class="form-control">
            <option value="" ruta='http://localhost/tabletopFinder/aplicacion/public/images/fondos/Default.jpg'>-</option>
            @foreach ($listado as $op)

                <?php var_dump($op->fondos); ?>

                <option value="{{ $op->nombre }}" ruta="{{ url($op->ruta) }}">
                    {{ $op->nombre}}
                </option>
            @endforeach
        </select>
        @error('juegos')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
        @enderror
    </div>