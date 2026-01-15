@extends('plantilla1')
@section('encabezado', 'Crear Jugador')
@section('contenido')
    <form action="crearJugador.php" method="POST" class="card p-4 shadow">
        <input type="text" name="nombre" placeholder="Nombre" class="form-control mb-2" required>
        <input type="text" name="apellidos" placeholder="Apellidos" class="form-control mb-2" required>
        <input type="number" name="dorsal" placeholder="Dorsal" class="form-control mb-2" required>
        <select name="posicion" class="form-select mb-3">
            <option>Portero</option><option>Defensa</option><option>Centrocampista</option><option>Delantero</option>
        </select>
        <button type="submit" name="btnCrear" class="btn btn-primary">Guardar</button>
        <a href="jugadores.php" class="btn btn-link">Volver</a>
    </form>
@endsection