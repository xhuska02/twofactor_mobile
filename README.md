# Přidání dvoufaktorové autentizace pro Nextcloud / Adding Two-Factor Authentication for Nextcloud

Po nainstalování Nextcloud je třeba projekt přidat do projektu: / After installing Nextcloud, you need to add the project to the project:

1. **Přidat záznam do `docker-compose.yml` / Add an entry to `docker-compose.yml`:**
    ```yaml
    - type: bind
      source: ./nextcloud_apps/twofactormobile
      target: /var/www/html/custom_apps/twofactormobile
    ```

2. **Stáhnout projekt z GitHubu / Download the project from GitHub:**
    [https://github.com/xhuska02/twofactor_mobile](https://github.com/xhuska02/twofactor_mobile)

3. **Nakopírovat projekt do `<nextcloud-path>/apps` repozitáře / Copy the project to the `<nextcloud-path>/apps` repository.**

4. **Aktualizovat v projektu soubor `<twofactormobile/mob.json>` údaji z Firebase serveru ve formátu / Update the `<twofactormobile/mob.json>` file in the project with data from the Firebase server in the format:**
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
    ```
    Tyto údaje lze získat po založení a integraci Firebase. Následně je nutné také aktualizovat soubor `<twofactormobile/lib/Service/SendNotification.php>` vzhledem k vygenorovaným parametrům. / This information can be obtained after Firebase is created and integrated. Subsequently, you also need to update the `<twofactormobile/lib/Service/SendNotification.php>` file with respect to the generated parameters.

5. **Nainstalovat potřebné integrace pomocí `composer install` / Install the necessary integrations using `composer install`.**

6. **Aktivovat aplikaci pomocí `occ app:enable twofactormobile` / Activate the application using `occ app:enable twofactormobile`.**

7. **Pro vypnutí vícefaktorové autentizace pro uživatele lze použít příkaz / To disable multifactor authentication for users, you can use the command:**
    ```bash
    occ twofactorauth:disable <userID> twofactormobile
    ```

## Známé problémy / Known Problems

Není předáno z přihlašovací stránky - porušuje následující směrnici Zásady zabezpečení obsahu. / Not forwarded from login

page - violates the following Content Security Policy directive.

Vyřešeno přidáním do konfigurace serveru `/var/www/html/config/config.php` IP adresy serveru do důvěryhodných domén: / Resolved by adding the server's IP address to `/var/www/html/config/config.php` in the server configuration to trusted domains:
```php
'trusted_domains' => 
  array (
    0 => 'localhost',
    1 => '192.168.1.38',
  ),

