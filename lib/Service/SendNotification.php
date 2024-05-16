<?php

/**
 *
 * @copyright Copyright (c) 2022, Luděk Huška (xhuska02@vutbr.cz)
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero Genera$this->userConfigl Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */


declare(strict_types=1);

namespace OCA\TwofactorMobile\Service;


use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class SendNotification{


    public function sendNotification($userToken, $userName, $challenge): string
	{
        
        require '/var/www/html/custom_apps/twofactormobile/vendor/autoload.php'; 


        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents('/var/www/html/custom_apps/twofactormobile/mob.json'), true)
        );

        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        $ch = curl_init("https://fcm.googleapis.com/v1/projects/mobiletwofactordect/messages:send");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token['access_token']
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, '{
                "message": {
                    "token": "' . $userToken . '",
                "notification": {
                    "title": "Prosím autorizujte se v aplikaci",
                    "body": "' . $userName . '",
                },
                "data": {
                    "title": "Prosím autorizujte se v aplikaci",
                    "body": "' . $userName . '",
                    "challenge": "' . $challenge . '"
                },
                "webpush": {
                    "fcm_options": {
                    "link": "https://google.com"
                    }
                }
                }
            }');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Nastavení pro návrat odpovědi z curl

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
		
	}



}


