<?php

// Definer API-URL og API-nøkkel
$apiUrl = 'https://api.cristin.no/v2/persons';


// Hent søketerm fra GET-parameter
$searchTerm = strtolower($_GET['q']);

// Konstruer API-forespørsel
$url = $apiUrl . '?query=' . urlencode($searchTerm);



// Utfør cURL-forespørsel
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Dekod JSON-respons
$data = json_decode($response, true);

// Sjekk for feil
if ($data['error']) {
    echo json_encode(['error' => $data['error']['message']]);
    exit;
}

// Filtrer personer basert på søketerm
$filteredPersons = [];
foreach ($data['items'] as $person) {
    $name = strtolower($person['name']['full']);
    if (strpos($name, $searchTerm) !== false) {
        $filteredPersons[] = [
            'navn' => $person['name']['full'],
            'arbeidssted' => $person['organisations'][0]['name'],
            'stillingstittel' => $person['positions'][0]['title'],
            'id' => $person['id'],
        ];
    }
}

// Returner JSON-data med filtrerte personer
echo json_encode($filteredPersons);
