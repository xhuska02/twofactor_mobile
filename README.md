# Přidání dvoufaktorové autentizace pro nextcloud
# Po nainstalování nextcloud je třeba projekt přidat do projektu:

1) Přidat záznam do docker-compose.yml
      - type: bind
        source:  ./nextcloud_apps/twofactormobile
        target: /var/www/html/custom_apps/twofactormobile

2) Stáhnout projekt z githubu https://github.com/xhuska02/twofactor_mobile
3) nakopírovat projekt do `<nextcloud-path>/apps` repozitáře
4) aktualizovat v projektu soubor `<twofactormobile\mob.json>` údaji z Firebase serveru ve formátu: 
{
  "type": "service_account",
  "project_id": "mobiletwofactordect",
  "private_key_id": 
  "private_key": 
  "client_email": 
  "client_id":
  "auth_uri": 
  "token_uri": 
  "auth_provider_x509_cert_url": 
  "client_x509_cert_url": 
  "universe_domain": 
}

Tyto údaje lze získat po založení a integraci firebase. Následně je nutné taktéž aktualizovat soubor `<twofactormobile\lib\Service\SendNotification.php>` vzhledem k vygenorovaným parametrům.

5) Nainstalovat potřebné integrace pomocí `composer install`
6) Aktivovat aplikaci pomocí `occ app:enable twofactormobile`
7) pro vypnutí vícefaktorové autentizace pro uživatele lze použít příkaz * `occ twofactorauth:disable <userID> twofactormobile`


# Známé problémy
Není předáno z přihlašovací stránky - porušuje následující směrnici Zásady zabezpečení obsahu 

Vyřešeno přidáním do konfigurace serveru `/var/www/html/config/config.php` ip adresy serveru do důvěryhodných domén:
'trusted_domains' => 
  array (
    0 => 'localhost',
    1 => '192.168.1.38',
  ),

------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

# Adding two-factor authentication for nextcloud
# After installing nextcloud, you need to add the project to the project:

1) Add an entry to docker-compose.yml
      - type: bind
        source: ./nextcloud_apps/twofactormobile
        target: /var/www/html/custom_apps/twofactormobile

2) Download the project from github https://github.com/xhuska02/twofactor_mobile
3) copy the project to the `<nextcloud-path>/apps` repository
4) update the `<twofactormobile\mob.json>` file in the project with the data from the Firebase server in the format: 
{
  "type": "service_account",
  "project_id": "mobiletwofactordect",
  "private_key_id": 
  "private_key": 
  "client_email": 
  "client_id":
  "auth_uri": 
  "token_uri": 
  "auth_provider_x509_cert_url": 
  "client_x509_cert_url": 
  "universe_domain": 
}

This information can be obtained after firebase is created and integrated. Afterwards, the `<twofactormobile\lib\Service\SendNotification.php>` file must also be updated with respect to the generated parameters.

5) Install the necessary integrations using `composer install`.
6) Activate the application using `occ app:enable twofactormobile`
7) To disable multi-factor authentication for a user, you can use * `occ twofactorauth:disable <userID> twofactormobile`


# Known issues
Not forwarded from login page - violates the following Content Security Policy directive 

Solved by adding into server config `/var/www/html/config/config.php` ip adress of server into trusted domains:
'trusted_domains' => 
  array (
    0 => 'localhost',
    1 => '192.168.1.38',
  ),
