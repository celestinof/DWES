<?php
// Intentamos recibir por POST (ruta completa) o por GET (marcador individual)
$puntos = $_POST['pos'] ?? [];

// Si venimos de un clic individual en repartos.php
if (isset($_GET['coords'])) {
    $puntos[] = $_GET['coords'];
}

if (empty($puntos) || $puntos[0] == ",") {
    die("No hay coordenadas válidas para mostrar el mapa.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruta de Reparto</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>#map { height: 600px; width: 100%; border-radius: 10px; }</style>
</head>
<body style="background-color: #00bfa5; padding: 20px;">
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <h2 style="text-align: center;">Mapa de Ruta Optimizado</h2>
        <div id="map"></div>
        <div style="text-align: center; margin-top: 15px;">
            <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Cerrar Mapa</button>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([36.838, -2.459], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Dibujar los marcadores
        var latlngs = [];
        <?php foreach ($puntos as $p): ?>
            var coords = "<?php echo $p; ?>".split(',');
            var pos = [parseFloat(coords[0]), parseFloat(coords[1])];
            L.marker(pos).addTo(map);
            latlngs.push(pos);
        <?php endforeach; ?>

        // Dibujar la línea que une los puntos
        var polyline = L.polyline(latlngs, {color: 'red', weight: 5}).addTo(map);
        map.fitBounds(polyline.getBounds());
    </script>
</body>
</html>