const searchBox = document.getElementById('searchBox');
const resultsList = document.getElementById('resultsList');
const addedPersons = document.getElementById('addedPersons');

let timeoutId = null; // Variable to store the timeout ID
const addedPersonIds = []; // Opprett en tom liste for å lagre person-ID-ene
const idToPerson = {}
function leggTilPerson(personId, navn) {
    addedPersonIds.push = personId;
    idToPerson[personId] = navn;
}
document.getElementById('showPublicationsButton').addEventListener('click', async () => {
const mainBox = document.getElementById('mainBox');
mainBox.innerHTML = "";
    // Send separate spørringer for hver person
    for (const personId of addedPersonIds) {
        try {
            const response = await fetch(`getPublications.php?id=${personId}`);
            const data = await response.text();

            console.log(`Publikasjoner for person ${personId}:`, data);
            const p = document.createElement('p');
            const h1 = document.createElement('h1');
            h1.innerText = idToPerson[personId]
            p.innerHTML = p.innerHTML + data;
            console.log(data)
            mainBox.innerHTML += p
        } catch (error) {
            console.error('Feil ved henting av publikasjoner:', error);
        }
    }
});
searchBox.addEventListener('input', () => {
    let searchTerm = searchBox.value.toLowerCase();
    searchTerm = searchTerm.replace(/ /g, "+");
    console.log(searchTerm);
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
                            listItem.textContent = person.navn;
                            resultsList.appendChild(listItem);

                            person.affiliations.forEach(affiliation => {
                                const subListItem = document.createElement('ul');
                                subListItem.textContent = `${affiliation.institution} (${affiliation.position})`;
                                listItem.appendChild(subListItem);
                            });

                            const addButton = document.createElement('button');
                            addButton.textContent = 'Legg til';
                            addButton.addEventListener('click', () => {
                                const addedPerson = document.createElement('li');
                                addedPerson.textContent = person.navn;
                                leggTilPerson(person.cristin_person_id);

                                const removeButton = document.createElement('button');
                                removeButton.textContent = 'Fjern';
                                removeButton.addEventListener('click', () => {
                                    addedPersons.removeChild(addedPerson);
                                });

                                addedPerson.appendChild(removeButton);
                                addedPersons.appendChild(addedPerson);
                            });

                            listItem.appendChild(addButton);
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
function visPublikasjoner(personId) {
    // Her kan du implementere koden for å hente publikasjonsdata basert på personId
    // For eksempel:
    fetch(`getPublications.php?id=${personId}&after_year=2019`)
        .then(response => response.json())
        .then(publications => {
            // Vis publikasjonsdata i et passende format (f.eks. en liste)
            console.log(publications);
        })
        .catch(error => {
            console.error('Feil ved henting av publikasjoner:', error);
        });
}
