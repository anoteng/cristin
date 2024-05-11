<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define API URLs
$apiUrlPersons = 'https://api.cristin.no/v2/persons';
$apiUrlInstitutions = 'https://api.cristin.no/v2/institutions';
class InstitutionLookup {
    private $apiUrlInstitutions = 'https://api.cristin.no/v2/institutions';
    private $institutionNames = [];

    public function getInstitutionName($institutionID) {
        if (array_key_exists($institutionID, $this->institutionNames)) {
            return $this->institutionNames[$institutionID];
        } else {
            $institutionDetails = $this->fetchInstitutionDetails($institutionID);
            $this->institutionNames[$institutionID] = $institutionDetails['institution_name']['en'];
            return $this->institutionNames[$institutionID];
        }
    }

    private function fetchInstitutionDetails($institutionID) {
        $urlInstitutionDetails = $this->apiUrlInstitutions ."/". $institutionID;
        $chInstitutionDetails = curl_init($urlInstitutionDetails);
        curl_setopt($chInstitutionDetails, CURLOPT_RETURNTRANSFER, true);
        $responseInstitutionDetails = curl_exec($chInstitutionDetails);
        curl_close($chInstitutionDetails);

        return json_decode($responseInstitutionDetails, true);
    }
}
$institutionLookup = new InstitutionLookup();
// Fetch search term from GET parameter
$searchTerm = preg_replace('/\s+/', '+', urldecode(strtolower($_GET['q'])));
//echo $searchTerm;

// Construct API request for initial search
$urlPersons = $apiUrlPersons . '?name=' . $searchTerm;
//echo $urlPersons;
// Execute cURL request for initial search
$chPersons = curl_init($urlPersons);
curl_setopt($chPersons, CURLOPT_RETURNTRANSFER, true);
$responsePersons = curl_exec($chPersons);
curl_close($chPersons);

// Decode JSON response for initial search
$dataPersons = json_decode($responsePersons, true);
//var_dump($dataPersons[0]);
// Filter persons based on search term
$filteredPersons = [];
foreach ($dataPersons as $person) {
    $name = strtolower($person['surname'] . ", " . $person['first_name']);
    $filteredPersons[] = [
        'navn' => $name,
        'cristin_person_id' => $person['cristin_person_id'],
    ];
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

    // Extract relevant person details
    $affiliations = [];
    foreach ($personDetailsData['affiliations'] as $affiliation) {
        $affiliations[] = [
            'institution' => $institutionLookup->getInstitutionName($affiliation['institution']['cristin_institution_id']),
            'position' => $affiliation['position']['en'] ?? '',
        ];
    }

    $personDetails[] = [
        'navn' => $personDetailsData['first_name'] . ' ' . $personDetailsData['surname'],
        'cristin_person_id' => $personDetailsData['cristin_person_id'],
        'affiliations' => $affiliations,
    ];
}

// Return JSON data with filtered and enriched persons
echo json_encode($personDetails);
