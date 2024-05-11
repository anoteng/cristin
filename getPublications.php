<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$url = "https://api.cristin.no/v2/persons/". $_GET["id"] ."/results";
$year = $_GET["after_year"];
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result, true);
?>
<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--<title>Uttak fra Cristin</title>-->
<!--<style>-->
<!--table, th, td {-->
<!--  border: 1px solid;-->
<!--}-->
<!--</style>-->
<!--</head>-->
<!--<body>-->
<table>
<tr>
	<th>Tittel</th>
	<th>Årstall</th>
	<th>Type</th>
	<th>Forfattere</th>
</tr>
<?php
foreach($obj as $i){
	
	if($i["year_published"] >= $year and ($i["category"]["code"] == "ARTICLE" or $i["category"]["code"] == "ACADEMICREVIEW" or $i["category"]["code"] == "CHAPTERACADEMIC" )){
		echo "<tr>";
		echo "<td>";
#		if(empty($i["title"]["en"])){
#			echo $i["title"]["nb"];
#		}else{
#			echo $i["title"]["en"];
#		}
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
<!--</body>-->
<!--</html>-->
