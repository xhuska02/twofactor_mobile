# Přidání dvoufaktorové autentizace pro Nextcloud

Po nainstalování Nextcloud je třeba projekt přidat do projektu:

1. **Přidat záznam do `docker-compose.yml`:**
    ```yaml
    - type: bind
      source: ./nextcloud_apps/twofactormobile
      target: /var/www/html/custom_apps/twofactormobile
    ```

2. **Stáhnout projekt z GitHubu:**
    [https://github.com/xhuska02/twofactor_mobile](https://github.com/xhuska02/twofactor_mobile)

3. **Nakopírovat projekt do `<nextcloud-path>/apps` repozitáře.**

4. **Aktualizovat v projektu soubor `<twofactormobile/mob.json>` údaji z Firebase serveru ve formátu:**
    ```json
    {
      "type": "service_account",
      "project_id": "mobiletwofactordect",
      "private_key_id": "",
      "private_key": "",
      "client_email": "",
      "client_id": "",
      "auth_uri": "",
      "token_uri": "",
      "auth_provider_x509_cert_url": "",
      "client_x509_cert_url": "",
      "universe_domain": ""
    }
    Tyto údaje lze získat po založení a integraci Firebase. Následně je nutné také aktualizovat soubor `<twofactormobile/lib/Service/SendNotification.php>` vzhledem k vygenorovaným parametrům.

5. **Nainstalovat potřebné integrace pomocí `composer install`.**

6. **Aktivovat aplikaci pomocí `occ app:enable twofactormobile`.**

7. **Pro vypnutí vícefaktorové autentizace pro uživatele lze použít příkaz:**
    ```bash
    occ twofactorauth:disable <userID> twofactormobile
    ```

## Známé problémy

Není předáno z přihlašovací stránky - porušuje následující směrnici Zásady zabezpečení obsahu.

Vyřešeno přidáním do konfigurace serveru `/var/www/html/config/config.php` IP adresy serveru do důvěryhodných domén:
```php
'trusted_domains' => 
  array (
    0 => 'localhost',
    1 => '192.168.1.38',
  ),
```
    
# Adding two-factor authentication for Nextcloud

After installing Nextcloud, you need to add the project to the project:

1. **Add an entry to `docker-compose.yml`:**
    ```yaml
    - type: bind
      source: ./nextcloud_apps/twofactormobile
      target: /var/www/html/custom_apps/twofactormobile
    ```

2. **Download the project from GitHub:**
    [https://github.com/xhuska02/twofactor_mobile](https://github.com/xhuska02/twofactor_mobile)

3. ** Copy the project to the `<nextcloud-path>/apps` repository.**

4. **Update the `<twofactormobile/mob.json>` file in the project with data from the Firebase server in the format:**
    ```json
    {
      "type": "service_account",
      "project_id": "mobiletwofactordect",
      "private_key_id": "",
      "private_key": "",
      "client_email": "",
      "client_id": "",
      "auth_uri": "",
      "token_uri": "",
      "auth_provider_x509_cert_url": "",
      "client_x509_cert_url": "",
      "universe_domain": ""
    }
    This information can be obtained after Firebase is created and integrated. Subsequently, you also need to update the `<twofactormobile/lib/Service/SendNotification.php>` file with respect to the generated parameters.

5. **Install the necessary integrations using `composer install`.**

6. **Activate the application using `occ app:enable twofactormobile`.**

7. **To disable multifactor authentication for users, you can use the command:**
    ```bash
    occ twofactorauth:disable <userID> twofactormobile
    ```
## Known issues

Not forwarded from the login page - violates the following Content Security Policy directive.

Resolved by adding the server's `/var/www/html/config/config.php` IP address to trusted domains in the server configuration:
```php
'trusted_domains' => 
  array (
    0 => 'localhost',
    1 => '192.168.1.38',
  ),
```