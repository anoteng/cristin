const searchBox = document.getElementById('searchBox');
const resultsList = document.getElementById('resultsList');

let timeoutId = null; // Variable to store the timeout ID
searchBox.addEventListener('input', () => {
    const searchTerm = searchBox.value.toLowerCase();
    // Debounce the fetch operation using a timeout
    if (timeoutId) {
        clearTimeout(timeoutId); // Clear any existing timeout
    }
    timeoutId = setTimeout(() => {
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
    }, 500); // Debounce delay (in milliseconds)

});
