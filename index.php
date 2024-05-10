<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Venstremeny Mal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <nav class="nav flex-column">
                <a class="nav-link active" aria-current="page" href="#">Hjem</a>
                <a class="nav-link" href="#">Om oss</a>
                <a class="nav-link" href="#">Tjenester</a>
                <a class="nav-link" href="#" onclick="visPublikasjoner(personId)">Vis publikasjoner</a>
                <ul id="addedPersons"></ul>
            </nav>
        </div>

        <div class="col-md-9">
            <h1>Hent data og statistikk fra Cristin</h1>
            <p>Legg til personer du vil hente dato om under</p>
            <p><label for="searchBox">Personnavn: </label><input type="text" id="searchBox"></p>
            <p><ul id="resultsList"></ul></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
<script src="script.js"></script>
</html>
