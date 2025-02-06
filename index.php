<?php
// index.php
?>

<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cristin Publikasjonssøk</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Enkel stil for søkefeltet */
        #search-results {
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background: #fff;
            width: 100%;
            z-index: 1000;
        }
        #search-results div {
            padding: 8px;
            cursor: pointer;
        }
        #search-results div:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Søk etter publikasjoner</h1>
    <form id="search-form" action="getPublications.php" method="GET">
        <label for="name">Navn:</label><br>
        <input type="text" id="name" name="name" autocomplete="off">
        <div id="search-results"></div>
        <br><br>

        <label for="cristin_id">Cristin-ID:</label><br>
        <input type="text" id="cristin_id" name="id">
        <br><br>

        <label for="after_year">Fra og med år:</label><br>
        <input type="number" id="after_year" name="after_year" min="1900" max="<?php echo date('Y'); ?>" value="2018">
        <br><br>

        <label for="aacsb">Ta med oppsummering av ICs for AACSB:</label>
        <input type="checkbox" id="aacsb" name="AACSB" value="1" checked>
        <br><br>

        <button type="submit">Søk</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                let query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: 'https://api.cristin.no/v2/persons',
                        method: 'GET',
                        data: { name: query },
                        success: function(data) {
                            $('#search-results').empty();
                            if (data.length > 0) {
                                data.forEach(person => {
                                    $('#search-results').append('<div data-id="' + person.cristin_person_id + '">' + person.full_name + '</div>');
                                });
                            } else {
                                $('#search-results').append('<div>Ingen resultater</div>');
                            }
                        }
                    });
                } else {
                    $('#search-results').empty();
                }
            });

            // Når man klikker på et navn fra søkeresultatet
            $('#search-results').on('click', 'div', function() {
                $('#name').val($(this).text());
                $('#cristin_id').val($(this).data('id'));
                $('#search-results').empty();
            });
        });
    </script>
</body>
</html>