const searchBox = document.getElementById('searchBox');
const resultsList = document.getElementById('resultsList');

searchBox.addEventListener('input', () => {
    const searchTerm = searchBox.value.toLowerCase();

    if (searchTerm.length > 2) {
        fetch(`getPerson.php?q=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                resultsList.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(person => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${person.navn} - ${person.arbeidssted} (${person.stillingstittel})`;
                        resultsList.appendChild(listItem);
                    });
                } else {
                    resultsList.innerHTML = '<li>Ingen resultater funnet</li>';
                }
            })
            .catch(error => {
                console.error('Feil ved henting av personer:', error);
            });
    } else {
        resultsList.innerHTML = '';
    }
});
