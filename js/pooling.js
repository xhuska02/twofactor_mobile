document.addEventListener("DOMContentLoaded", function() {
    const nonceValue = document.querySelector("#nonce-user").value;
    console.log("Nonce hodnota:", nonceValue);

    function submitForm() {
        document.getElementById("admin-2fa-form").submit();
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
                console.log("Send submitForm");
                setTimeout(submitForm, 1000);
            }
        })
        .catch(error => {
            console.error('Nastala chyba:', error);
        });
    }
    
    setInterval(getAPIState, 3000);
    //submitForm();

    //setTimeout(submitForm, 10000);
    //setInterval(submitForm, 20000);
});
