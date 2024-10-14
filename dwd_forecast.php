<?php

/*

    Die Datei dient lediglich zur Ausgabe der Genauen Temperatur des Tages 

    Eine Manuelle Eingabe wäre hier Unötig, die API die ich hier nutze ( DWD ) führt zum heutigen Zeitpunkt keine ordentlich funktionierende 
    Liste der Wetterstationen mit Stations IDs, deswegen wird hier eine Feste Stationnummer benutzt ( Berlin-Tempelhof )

*/


header('Content-Type: application/json');

// API-URL für die DWD-Station mit derzeitiger Ortsangabe
$api_url = "https://dwd.api.proxy.bund.dev/v30/stationOverviewExtended?stationIds=10384";

// cURL initialisieren
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// API-Antwort abrufen
$response = curl_exec($ch);

// Prüfen, ob ein Fehler aufgetreten ist
if (curl_errno($ch)) {
    echo json_encode(['error' => 'cURL Fehler: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

// API-Daten in ein Array dekodieren
$json_data = json_decode($response, true);

// Überprüfen auf Fehler beim Dekodieren
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON-Dekodierungsfehler: ' . json_last_error_msg()]);
    curl_close($ch);
    exit;
}

// Schließe cURL
curl_close($ch);

// Prüfen, ob Vorhersagedaten vorhanden sind
if (!empty($json_data['10384']['forecast1'])) {
    $currentTime = time();
    $daysTime = $json_data['10384']['forecast1'];
    $start = $daysTime['start'] / 1000;
    $time = floor(($currentTime - $start) / 3600); // Zeit in Stunden

    // Sicherstellen, dass $time ein gültiger Index ist
    if ($time >= 0 && $time < count($daysTime['temperature'])) {
        $temperature_today = $daysTime['temperature'][$time] / 10; // Temperatur in °C umwandeln        
        echo json_encode([
            'temperatureToday' => $temperature_today,
            'message' => $temperature_today . "°C"
        ]);
    } else {
        echo json_encode(['error' => 'Ungültiger Temperaturindex: ' . $time]);
    }
} else {
    echo json_encode(['error' => 'Keine Daten von der API verfügbar.']);
}
