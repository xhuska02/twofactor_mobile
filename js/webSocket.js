const lang = "en";


window.onload = function () {
    const nonceValue = document.querySelector("#webeid-nonce").value;   
    //alert("Hodnota nonce: " + nonceValue);

    async function getAPIState() {
        try {
            const response = await fetch('https://localhost:8443/index.php/apps/twofactormobile/api/1.0/hello', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ "uid": nonceValue }),
            });
            
            if (!response.ok) {
                throw new Error(`Chyba při provádění požadavku: ${response.statusText}`);
            }
    
            const data = await response.json();
            console.log(data);
            //alert(data);
    
            // Pokud přijde hodnota true, provede se funkce submitForm()
            if (data == "true") {
                //alert("true");
                
            }
        } catch (error) {
            console.error('Nastala chyba:', error);
        }
    }
    
    
    setInterval(getAPIState, 3000);
    
    
}
