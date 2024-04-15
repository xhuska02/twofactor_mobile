import fetch from 'node-fetch';
import https from 'https'; // Import modulu https

async function getAPIState() {
    try {
        const response = await fetch('https://192.168.1.239:8443/index.php/apps/twofactormobile/api/1.0/hello', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "uid": "vysetrovatel" }),
            // Použití modulu https pro vytvoření agenta s rejectUnauthorized nastaveným na false
            agent: new https.Agent({ rejectUnauthorized: false }),
        });
        
        if (!response.ok) {
            throw new Error(`Chyba při provádění požadavku: ${response.statusText}`);
        }

        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Nastala chyba:', error);
    }
}

setInterval(getAPIState, 1000);


