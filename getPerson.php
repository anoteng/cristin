<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Definer API-URL og API-nøkkel
$apiUrl = 'https://api.cristin.no/v2/persons';


// Hent søketerm fra GET-parameter
$searchTerm = strtolower($_GET['q']);

// Konstruer API-forespørsel
$url = $apiUrl . '?name=' . urlencode($searchTerm);



// Utfør cURL-forespørsel
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Dekod JSON-respons
$data = json_decode($response, true);

// Filtrer personer basert på søketerm
$filteredPersons = [];
foreach ($data as $person) {
    $name = strtolower($person['surname']. ", ". $person['first_name']);
    if (strpos($name, $searchTerm) !== false) {
        $filteredPersons[] = [
            'navn' => $name,
//            'arbeidssted' => $person['organisations'][0]['name'],
//            'stillingstittel' => $person['positions'][0]['title'],
            'id' => $person['cristin_person_id'],
        ];
    }
}

// Returner JSON-data med filtrerte personer
echo json_encode($filteredPersons);
