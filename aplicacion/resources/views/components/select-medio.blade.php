<div class="form-group">
        <label for="body">Medio de la partida</label>
        <small class="crearanuncio-help">El medio por el que se jugará tu partida</small>
        <select name="medio" class="form-control">
            <option value="online">Online</option>
            <option value="fisico">Físico</option>
        </select>
        @error('medio')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
        @enderror
    </div>