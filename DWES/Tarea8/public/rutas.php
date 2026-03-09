<?php
// Recibimos el array de coordenadas 'pos' que enviamos desde el formulario de repartos.php
$puntos = $_POST['pos'] ?? [];

if (empty($puntos)) {
    die("No hay puntos de entrega para mostrar la ruta.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ruta Completa de Reparto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    
    <style>
        #map { height: 550px; width: 100%; border-radius: 10px; border: 2px solid #343a40; }
        
        /* Ocultamos el cajetín de las indicaciones de texto para que el mapa se vea completo */
        .leaflet-routing-container { display: none !important; }
    </style>
</head>
<body style="background:#00bfa5; padding-top: 20px;">
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-dark text-white text-center">
                <h4><i class="fas fa-route mr-2"></i>Hoja de Ruta de Repartidor</h4>
            </div>
            <div class="card-body">
                <div id="map"></div>
            </div>
            <div class="card-footer text-center">
                <button onclick="window.close()" class="btn btn-primary">Cerrar Ventana</button>
            </div>
        </div>
    </div>

    <script>
        // 1. Inicializar el mapa centrado en Almería
        var map = L.map('map').setView([36.838, -2.459], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Array para guardar los puntos de ruta (waypoints)
        var waypoints = [];

        // 2. Extraer las coordenadas del PHP y convertirlas al formato que pide la ruta
        <?php foreach($puntos as $index => $p): ?>
            var coords = "<?php echo $p; ?>".split(',');
            var lat = parseFloat(coords[0]);
            var lon = parseFloat(coords[1]);
            
            // L.latLng es el formato exacto que pide el Routing Machine
            waypoints.push(L.latLng(lat, lon));
        <?php endforeach; ?>

        // 3. Dibujar la ruta mágica por las calles
        L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,  // Desactiva que se recalcule si el usuario arrastra la línea
            addWaypoints: false,        // Evita que el usuario añada paradas haciendo clic
            fitSelectedRoutes: true,    // Hace zoom automático para que quepa toda la ruta
            show: false,                // Esconde el panel de instrucciones de giro a giro
            lineOptions: {
                styles: [{color: '#28a745', opacity: 0.8, weight: 6}] // Línea verde gordita
            },
            // Dibujamos nuestros marcadores personalizados de paradas
            createMarker: function(i, waypoint, n) {
                return L.marker(waypoint.latLng)
                    .bindPopup("<b>Parada " + (i + 1) + "</b>");
            }
        }).addTo(map);

    </script>
</body>
</html>