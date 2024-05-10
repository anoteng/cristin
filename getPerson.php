<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define API URLs
$apiUrlPersons = 'https://api.cristin.no/v2/persons';
$apiUrlInstitutions = 'https://api.cristin.no/v2/institutions';

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
    $personDetails[] = json_decode($responsePersonDetails, true);
//    // Extract relevant person details
//    $personDetails[] = array_merge($person, [
//        'arbeidssted' => $personDetailsData['organisations'][0]['name'] ?? '',
//        'stillingstittel' => $personDetailsData['positions'][0]['title'] ?? '',
//    ]);
}
// Fetch institution details using CRISTIN institution IDs
$institutionDetails = [];
foreach ($personDetails as $person) {
    $institutionID = $person['institution_id']; // Assuming the institution ID is available in the person details

    // Check if we already have the institution details
    if (!array_key_exists($institutionID, $institutionDetails)) {
        $urlInstitutionDetails = $apiUrlInstitutions ."/". $institutionID;
        $chInstitutionDetails = curl_init($urlInstitutionDetails);
        curl_setopt($chInstitutionDetails, CURLOPT_RETURNTRANSFER, true);
        $responseInstitutionDetails = curl_exec($chInstitutionDetails);
        curl_close($chInstitutionDetails);

        $institutionDetails[$institutionID] = json_decode($responseInstitutionDetails, true);
    }
}
// Return JSON data with filtered and enriched persons
echo json_encode($personDetails);
