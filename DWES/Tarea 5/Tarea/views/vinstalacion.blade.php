@extends('plantilla1')
@section('titulo', 'Instalación')
@section('encabezado', 'Instalación del Sistema')
@section('contenido')
    <div class="alert alert-warning text-center" role="alert">
        <h4 class="alert-heading">¡Base de datos vacía!</h4>
        <p>No se han encontrado jugadores en la base de datos.</p>
        <hr>
        <p class="mb-0">Haz clic en el botón para generar datos de prueba aleatorios.</p>
    </div>
    <div class="text-center mt-4">
        <a href="crearDatos.php" class="btn btn-primary btn-lg">
            <i class="fas fa-database"></i> Instalar Datos de Ejemplo
        </a>
    </div>
@endsection