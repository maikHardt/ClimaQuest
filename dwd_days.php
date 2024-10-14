<?php

/*
    Die Datei dient zur Ausgabe der Daten für die nächsten Tage

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

// Schließe cURL
curl_close($ch);

if (!empty($json_data['10384']['days'])) {
    $daysData = [];

    // Beschränkung auf die ersten 5 Tage
    foreach (array_slice($json_data['10384']['days'], 0, 5) as $day) {
        $dayDate = $day['dayDate'] ?? 'Keine Daten';
        $temperature_min = isset($day['temperatureMin']) ? $day['temperatureMin'] / 10 : 'Keine Daten';
        $temperature_max = isset($day['temperatureMax']) ? $day['temperatureMax'] / 10 : 'Keine Daten';
        $precipitation = isset($day['precipitation']) ? $day['precipitation'] : '0';
        $windSpeed = isset($day['windSpeed']) ? $day['windSpeed'] / 10 : 'Keine Daten';
        $windGust = isset($day['windGust']) ? $day['windGust'] / 10 : 'Keine Daten';
        $windDirection = isset($day['windDirection']) ? getWindDirection($day['windDirection'] / 10) : 'Keine Daten';
        $sunrise = isset($day['sunrise']) ? date('H:i', $day['sunrise'] / 1000) : 'Keine Daten';
        $sunset = isset($day['sunset']) ? date('H:i', $day['sunset'] / 1000) : 'Keine Daten';
        $moonrise = isset($day['moonrise']) ? date('H:i', $day['moonrise'] / 1000) : 'Keine Daten';
        $moonset = isset($day['moonset']) ? date('H:i', $day['moonset'] / 1000) : 'Keine Daten';
        $moonPhase = $day['moonPhase'] ?? 'Keine Daten';

        // Daten für den Tag in das Array speichern
        $daysData[] = [
            'dayDate' => $dayDate,
            'temperatureMin' => $temperature_min,
            'temperatureMax' => $temperature_max,
            'precipitation' => $precipitation,
            'windSpeed' => $windSpeed,
            'windGust' => $windGust,
            'windDirection' => $windDirection,
            'sunrise' => $sunrise,
            'sunset' => $sunset,
            'moonrise' => $moonrise,
            'moonset' => $moonset,
            'moonPhase' => $moonPhase
        ];
    }

    // Ausgabe aller Tage als JSON
    echo json_encode($daysData);
} else {
    echo json_encode(['error' => 'Keine Daten von der API verfügbar.']);
}

// Funktion zur Umrechnung der Windrichtung
function getWindDirection($degrees) {
    $directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
    $index = round($degrees / 45) % 8; // Berechnung des Index
    return $directions[$index];
}
