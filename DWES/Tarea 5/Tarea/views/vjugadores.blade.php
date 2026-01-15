@extends('plantilla1')
@section('titulo', 'Listado')
@section('contenido')
    <div class="d-flex justify-content-between mb-3">
        <h3>Gestión de Jugadores</h3>
        <a href="fcrear.php" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo Jugador</a>
    </div>
    <table class="table table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nombre Completo</th>
                <th>Posición</th>
                <th>Dorsal</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arrayJugadores as $j)
            <tr class="align-middle">
                <td>{{$j['nombre']}} {{$j['apellidos']}}</td>
                <td><span class="badge bg-info text-dark">{{$j['posicion']}}</span></td>
                
                <td>{{ ($j['dorsal'] == 0) ? 'Sin asignar' : $j['dorsal'] }}</td>
                
                <td class="text-center">
                    <a href="jugadores.php?id_borrar={{$j['id']}}" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres borrar al jugador {{$j['nombre']}}?')">
                        <i class="fas fa-trash"></i> Borrar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection