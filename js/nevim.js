document.addEventListener("DOMContentLoaded", function() {
    const nonceValue = document.querySelector("#nonce-user").value;

    function submitForm() { 
        document.getElementById("admin-2fa-form").submit();
        console.log("subForm");
    }

    function getAPIState() {
        fetch('https://localhost:8443/index.php/apps/twofactormobile/api/1.0/hello', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "uid": nonceValue }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Chyba při provádění požadavku: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Odpověď z API:", data);
            // Pokud je hodnota true, vyvoláme funkci submitForm()
            if (data == "true") {
                //console.log("Send submitForm");
                submitForm();
            }
        })
        .catch(error => {

            console.error('Nastala chyba:', error);
            // Zde můžete provést další akce, pokud je to nutné
            // Například zobrazení chybového hlášení
        });
    }

    function test() {
        const requestUrl = 'https://localhost:8443/index.php/apps/twofactormobile/api/1.0/test';
        const requestOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        };
    
        // Logujeme detaily požadavku
        console.log('Odesílání požadavku:', requestUrl, requestOptions);
    
        fetch(requestUrl, requestOptions)
            .then(response => {
                console.log('Odpověď přijata:', {
                    status: response.status,
                    statusText: response.statusText,
                    headers: [...response.headers.entries()] // Převedení hlaviček na pole pro logování
                });
    
                // Pro lepší manipulaci a logování, je dobré tělo odpovědi zpracovat dále
                response.clone().text() // Klonujeme response, protože .text() nebo .json() může být použito pouze jednou
                    .then(text => console.log('Tělo odpovědi (text):', text))
                    .catch(textError => console.error('Chyba při čtení těla odpovědi jako text:', textError));
    
                response.clone().json() // Pokusíme se zpracovat tělo odpovědi jako JSON
                    .then(json => console.log('Tělo odpovědi (JSON):', json))
                    .catch(jsonError => console.error('Chyba při čtení těla odpovědi jako JSON:', jsonError));
    
                return response; // Vracíme originální response pro další řetězení, pokud je potřeba
            })
            .catch(error => {
                console.error('Nastala chyba:', error);
                // Zde můžete provést další akce, pokud je to nutné
                // Například zobrazení chybového hlášení
            });
    }

    setInterval(getAPIState,1000);
    //setTimeout(getAPIState,4000);

    //setInterval(test, 2000);

    //submitForm();

    //setTimeout(submitForm, 4000);
    //setInterval(submitForm, 20000);
});
