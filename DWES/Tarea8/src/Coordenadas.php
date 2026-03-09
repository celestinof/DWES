<?php

class Coordenadas
{
    public $direccion;

    public function __construct()
    {
        $num = func_num_args();
        if ($num == 1) {
            $this->direccion = func_get_arg(0);
        }
    }

    public function getCoordenadas()
    {
        // 1. Obtener Latitud y Longitud con Nominatim (OpenStreetMap)
        // Nominatim exige que nos identifiquemos con un User-Agent, por eso creamos este contexto
        $opts = [
            "http" => [
                "header" => "User-Agent: TareaRepartosDWES/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        
        $urlNominatim = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($this->direccion . ", Almeria, España");
        $salida = file_get_contents($urlNominatim, false, $context);
        $data = json_decode($salida, true);

        if (empty($data)) {
            return [0, 0, 0]; // Si no encuentra la calle, devolvemos ceros
        }

        $lat = $data[0]['lat'];
        $lon = $data[0]['lon'];

        // 2. Obtener la Altitud usando Open-Meteo (API gratuita y sin token)
        $urlElevacion = "https://api.open-meteo.com/v1/elevation?latitude={$lat}&longitude={$lon}";
        $salidaElev = @file_get_contents($urlElevacion);
        $alt = 0; // Valor por defecto
        
        if ($salidaElev) {
            $dataElev = json_decode($salidaElev, true);
            if (isset($dataElev['elevation'][0])) {
                $alt = $dataElev['elevation'][0];
            }
        }

        // Devolvemos el array con los 3 datos
        return [$lat, $lon, $alt];
    }

    public function ordenarEnvios($dato)
    {
        // OSRM espera las coordenadas en formato "longitud,latitud" (al revés que Bing y Google Maps)
        $puntos = explode("|", $dato);
        if (empty($dato) || count($puntos) == 0) return [];

        // Coordenadas ficticias del almacén como inicio (Lon, Lat)
        $coords = "-2.440779,36.86071;"; 
        
        foreach ($puntos as $p) {
            list($lat, $lon) = explode(",", $p);
            $coords .= trim($lon) . "," . trim($lat) . ";";
        }
        
        // Coordenadas ficticias del almacén como fin
        $coords .= "-2.440779,36.86071"; 

        // Usamos el servicio "trip" de OSRM para resolver el problema del viajante (optimizar ruta)
        $urlOSRM = "http://router.project-osrm.org/trip/v1/driving/" . $coords . "?source=first&destination=last&roundtrip=false";
        
        $opts = [
            "http" => [
                "header" => "User-Agent: TareaRepartosDWES/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $salida = file_get_contents($urlOSRM, false, $context);
        $data = json_decode($salida, true);

        $resp = [];
        if (isset($data['waypoints'])) {
            $order = [];
            // Omitimos el punto 0 (almacén inicio) y el último (almacén fin)
            // Nos interesan solo los envíos intermedios
            for ($i = 1; $i <= count($puntos); $i++) {
                $waypoint_index = $data['waypoints'][$i]['waypoint_index'];
                $order[$waypoint_index] = $i; // Guardamos su índice original (1, 2, 3...)
            }
            ksort($order); // Ordenamos el array por las claves (el nuevo orden de visita)
            $resp = array_values($order); // Extraemos solo los valores
        }
        
        return $resp;
    }
}