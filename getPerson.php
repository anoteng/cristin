<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define API URLs
$apiUrlPersons = 'https://api.cristin.no/v2/persons';

// Fetch search term from GET parameter
$searchTerm = strtolower($_GET['q']);

// Construct API request for initial search
$urlPersons = $apiUrlPersons . '?name=' . urlencode($searchTerm);

// Execute cURL request for initial search
$chPersons = curl_init($urlPersons);
curl_setopt($chPersons, CURLOPT_RETURNTRANSFER, true);
$responsePersons = curl_exec($chPersons);
curl_close($chPersons);

// Decode JSON response for initial search
$dataPersons = json_decode($responsePersons, true);

// Filter persons based on search term
$filteredPersons = [];
foreach ($dataPersons as $person) {
    $name = strtolower($person['surname'] . ", " . $person['first_name']);
    if (strpos($name, $searchTerm) !== false) {
        $filteredPersons[] = [
            'navn' => $name,
            'cristin_person_id' => $person['cristin_person_id'],
        ];
    }
}

// Fetch additional person details using CRISTIN person IDs
$personDetails = [];
foreach ($filteredPersons as $person) {
    $personID = $person['cristin_person_id'];

    $urlPersonDetails = $apiUrlPersons ."/". $personID;
    $chPersonDetails = curl_init($urlPersonDetails);
    curl_setopt($chPersonDetails, CURLOPT_RETURNTRANSFER, true);
    $responsePersonDetails = curl_exec($chPersonDetails);
    curl_close($chPersonDetails);

    $personDetailsData = json_decode($responsePersonDetails, true);
    var_dump($personDetailsData);
    // Extract relevant person details
    $personDetails[] = array_merge($person, [
        'arbeidssted' => $personDetailsData['organisations'][0]['name'] ?? '',
        'stillingstittel' => $personDetailsData['positions'][0]['title'] ?? '',
    ]);
}

// Return JSON data with filtered and enriched persons
echo json_encode($personDetails);
