    <div class="form-group">
        <label for="body">Juego</label>
        <small class="crearanuncio-help">Puedes ver el listado de juegos disponibles <a href="{{ route('juegos.listado') }}">aqui</a></small>
        <select name="juegocode" class="form-control">
            <option value="">-</option>
            @foreach ($listado as $op)

                <?php var_dump($op->juegos); ?>

                <option value="{{ $op->code }}">
                    {{ $op->nombre}}
                </option>
            @endforeach
        </select>
        @error('juegos')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
        @enderror
    </div>