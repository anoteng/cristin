<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$url = "https://api.cristin.no/v2/persons/". $_GET["id"] ."/results";
$year = $_GET["after_year"];
if(isset($_GET['AACSB'])){
	$AACSB = $_GET['AACSB'];
}else{
	$AACSB = false;
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result, true);
?>
<!DOCTYPE html>
<html>
<head>
<title>Uttak fra Cristin</title>
<style>
table, th, td {
  border: 1px solid;
}
</style>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<p>
		<a href="index.php">Tilbake til søkesiden</a>
	</p>
	<p>
	<h1><?php echo $_GET["name"]; ?></h1>
	<a href="https://app.cristin.no/persons/show.jsf?id=<?php echo $_GET["id"]; ?>" target="_blank">Cristin-profil</a> (åpner i ny fane)
	</p>
	<p>
		<?php
	$nvi_count = 0;

foreach ($results as $result) {
    if (isset($result['journal']['publisher']['nvi_level'])) {
        $nvi_level = $result['journal']['publisher']['nvi_level'];
        if ($nvi_level == '1' || $nvi_level == '2') {
            $nvi_count++;
        }
    }
}

// Skriv ut antall publikasjoner med nvi_level 1 eller 2
echo "Antall publikasjoner med NVI-nivå 1 eller 2: " . $nvi_count;
?>
</p>
	<?php
if($AACSB){
	$article = 0;
	$editorial = 0;
	$otherpres = 0;
	$anthology = 0;
	$monography = 0;
	$textbook = 0;
	$nonfiction = 0;
	$encyclopaedia = 0;
	$reference = 0;
	$compendium = 0;
	$articlepopular = 0;
	$feature = 0;
	$lecturepopular = 0;
	$report = 0;
	$doctordissertat = 0;
	$mediainterview = 0;
	$documentary = 0;
	$programmanage = 0;
	$chapteracademic = 0;
	$lexicalimport = 0;
	$other = 0;
	$academiclecture = 0;
	$popularbook = 0;
	$academicreview = 0;
	$patent = 0;
	$establbusiness = 0;
	$populararticle = 0;
	$articlejournal = 0;
	$lecture = 0;
	$chapter = 0;
	$interview = 0;
	foreach($obj as $i){
		if($i["year_published"] >= $year && $i["year_published"] <= $_GET["before_year"]){
			switch($i["category"]["code"]){
				case "ARTICLE":
					$article = $article + 1;
					break;
				case "EDITORIAL":
					$editorial = $editorial + 1;
					break;
				case "OTHERPRES":
					$otherpres = $otherpres + 1;
					break;
				case "ANTHOLOGYACA":
					$anthology = $anthology + 1;
					break;
				case "MONOGRAPHACA":
					$monography = $monography + 1;
					break;
				case "TEXTBOOK":
					$textbook = $textbook + 1;
					break;
				case "NONFICTIONBOOK":
					$nonfiction = $nonfiction + 1;
					break;
				case "ENCYCLOPAEDIA":
					$encyclopaedia = $encyclopaedia + 1;
					break;
				case "REFERENCEMATER":
					$reference = $reference + 1;
					break;
				case "COMPENDIUM":
					$compendium = $compendium + 1;
					break;
				case "ARTICLEPOPULAR":
					$articlepopular = $articlepopular + 1;
					break;
				case "ARTICLEFEATURE":
					$feature = $feature + 1;
					break;
				case "LECTUREPOPULAR":
					$lecturepopular = $lecturepopular + 1;
					break;
				case "REPORT":	
					$report = $report + 1;
					break;
				case "DOCTORDISSERTAT":
					$doctordissertat = $doctordissertat + 1;
					break;
				case "MEDIAINTERVIEW":
					$mediainterview = $mediainterview + 1;
					break;
				case "DOCUMENTARY":
					$documentary = $documentary + 1;
					break;
				case "PROGRAMMANAGE":
					$programmanage = $programmanage + 1;
					break;
				case "CHAPTERACADEMIC":
					$chapteracademic = $chapteracademic + 1;
					break;
				case "LEXICALIMPORT":
					$lexicalimport = $lexicalimport + 1;
					break;
				case "OTHER":
					$other = $other + 1;
					break;
				case "ACADEMICLECTURE":
					$academiclecture = $academiclecture + 1;
					break;
				case "POPULARBOOK":
					$popularbook = $popularbook + 1;
					break;
				case "ACADEMICREVIEW":
					$academicreview = $academicreview + 1;
					break;
				case "PATENT":
					$patent = $patent + 1;
					break;
				case "ESTABLBUSINESS":
					$establbusiness = $establbusiness + 1;
					break;
				case "POPULARARTICLE":
					$populararticle = $populararticle + 1;
					break;
				case "ARTICLEJOURNAL":
					$articlejournal = $articlejournal + 1;
					break;
				case "LECTURE":
					$lecture = $lecture + 1;
					break;
				case "CHAPTER":
					$chapter = $chapter + 1;
					break;
				case "INTERVIEW":
					$interview = $interview + 1;
					break;
			}
		}
	}
	?>
	<H2>Publikasjoner og andre ICs siden <?php echo $year; ?></H2>
	<p>
	<table>
	<tr>
		<?php
		if($article > 0){
			echo "<th>Artikkel</th>";
		}
		if($editorial > 0){
			echo "<th>Leder</th>";
		}
		if($otherpres > 0){
			echo "<th>Annen presentasjon</th>";
		}
		if($anthology > 0){
			echo "<th>Vitenskapelig Antologi / konferanseserie</th>";
		}
		if($monography > 0){
			echo "<th>Vitenskapelig monografi</th>";
		}
		if($textbook > 0){
			echo "<th>Lærebok</th>";
		}
		if($nonfiction > 0){
			echo "<th>Fagbok</th>";
		}
		if($encyclopaedia > 0){
			echo "<th>Leksikon</th>";
		}
		if($reference > 0){
			echo "<th>Oppslagsverk</th>";
		}
		if($compendium > 0){
			echo "<th>Kompendium</th>";
		}
		if($articlepopular > 0){
			echo "<th>Populærvitenskapelig artikkel</th>";
		}
		if($feature > 0){
			echo "<th>Kronikk</th>";
		}
		if($lecturepopular > 0){
			echo "<th>Populærvitenskapelig foredrag</th>";
		}
		if($report > 0){
			echo "<th>Rapport</th>";
		}
		if($doctordissertat > 0){
			echo "<th>Doktorgradsavhandling</th>";
		}
		if($mediainterview > 0){
			echo "<th>Intervju</th>";
		}
		if($documentary > 0){
			echo "<th>Dokumentar</th>";
		}
		if($programmanage > 0){
			echo "<th>Programledelse</th>";
		}
		if($chapteracademic > 0){
			echo "<th>Vitenskapelig kapittel</th>";
		}
		if($lexicalimport > 0){
			echo "<th>Leksikonartikkel</th>";
		}
		if($other > 0){
			echo "<th>Annet</th>";
		}
		if($academiclecture > 0){
			echo "<th>Vitenskapelig foredrag</th>";
		}
		if($popularbook > 0){
			echo "<th>Populærvitenskapelig bok</th>";
		}
		if($academicreview > 0){
			echo "<th>Vitenskapelig review</th>";
		}
		if($patent > 0){
			echo "<th>Patent</th>";
		}
		if($establbusiness > 0){
			echo "<th>Bedriftsetablering</th>";
		}
		if($populararticle > 0){
			echo "<th>Populærvitenskapelig artikkel</th>";
		}
		if($articlejournal > 0){
			echo "<th>Fagartikkel</th>";
		}
		if($lecture > 0){
			echo "<th>Faglig foredrag</th>";
		}
		if($chapter > 0){
			echo "<th>Faglig kapittel</th>";
		}
		if($interview > 0){
			echo "<th>Intervju tidsskrift</th>";
		}?>
	</tr>
	<tr>
		<?php
		if($article > 0){
			echo "<td>". $article ."</td>";
		}
		if($editorial > 0){
			echo "<td>". $editorial ."</td>";
		}
		if($otherpres > 0){
			echo "<td>". $otherpres ."</td>";
		}
		if($anthology > 0){
			echo "<td>". $anthology ."</td>";
		}
		if($monography > 0){
			echo "<td>". $monography ."</td>";
		}
		if($textbook > 0){
			echo "<td>". $textbook ."</td>";
		}
		if($nonfiction > 0){
			echo "<td>". $nonfiction ."</td>";
		}
		if($encyclopaedia > 0){
			echo "<td>". $encyclopaedia ."</td>";
		}
		if($reference > 0){
			echo "<td>". $reference ."</td>";
		}
		if($compendium > 0){
			echo "<td>". $compendium ."</td>";
		}
		if($articlepopular > 0){
			echo "<td>". $articlepopular ."</td>";
		}
		if($feature > 0){
			echo "<td>". $feature ."</td>";
		}
		if($lecturepopular > 0){
			echo "<td>". $lecturepopular ."</td>";
		}
		if($report > 0){
			echo "<td>". $report ."</td>";
		}
		if($doctordissertat > 0){
			echo "<td>". $doctordissertat ."</td>";
		}
		if($mediainterview > 0){
			echo "<td>". $mediainterview ."</td>";
		}
		if($documentary > 0){
			echo "<td>". $documentary ."</td>";
		}
		if($programmanage > 0){
			echo "<td>". $programmanage ."</td>";
		}
		if($chapteracademic > 0){
			echo "<td>". $chapteracademic ."</td>";
		}
		if($lexicalimport > 0){
			echo "<td>". $lexicalimport ."</td>";
		}
		if($other > 0){
			echo "<td>". $other ."</td>";
		}
		if($academiclecture > 0){
			echo "<td>". $academiclecture ."</td>";
		}
		if($popularbook > 0){
			echo "<td>". $popularbook ."</td>";
		}
		if($academicreview > 0){
			echo "<td>". $academicreview ."</td>";
		}
		if($patent > 0){
			echo "<td>". $patent ."</td>";
		}
		if($establbusiness > 0){
			echo "<td>". $establbusiness ."</td>";
		}
		if($populararticle > 0){
			echo "<td>". $populararticle ."</td>";
		}
		if($articlejournal > 0){
			echo "<td>". $articlejournal ."</td>";
		}
		if($lecture > 0){
			echo "<td>". $lecture ."</td>";
		}
		if($chapter > 0){
			echo "<td>". $chapter ."</td>";
		}
		if($interview > 0){
			echo "<td>". $interview ."</td>";
		}?>
	</tr>
	</table>
	</p>
	<?php
}
	?>
	<h2>Poenggivende publikasjoner</h2>
	<p>
<table>
<tr>
	<th>Tittel</th>
	<th>Årstall</th>
	<th>Type</th>
	<th>Forfattere</th>
</tr>
<?php
foreach($obj as $i){
	
	if($i["year_published"] >= $year and $i["year_published"] <= $_GET['before_year'] and ($i["category"]["code"] == "ARTICLE" or $i["category"]["code"] == "ACADEMICREVIEW" or $i["category"]["code"] == "CHAPTERACADEMIC" )){
		echo "<tr>";
		echo "<td>";
		echo $i["title"][$i["original_language"]];
		echo "</td>";
		echo "<td>";
		echo $i["year_published"];
		echo "</td>";
		echo "<td>";
		echo $i["category"]["name"]["en"];
		echo "</td>";
		echo "<td>";
		echo "<ol>";
		foreach( $i["contributors"]["preview"] as $j){
			echo "<li>". $j["surname"] .", ". $j["first_name"] ."</li>";
		}
		echo "</ol>";
		echo "</td>";
	}
}
?>
</table>
</p>
</body>
</html>
