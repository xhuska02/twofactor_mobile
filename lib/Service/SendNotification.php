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

// Just for testing purposes.
// $token = "fXQTOd8_T2e63Y9zqNarv5:APA91bEJiNih5dc0wz5-JC9NYDLr-afPzAYvarXo-2a7XLAKVvEI_J_UKSD2kDDyfDLjcn_fvm0Q1nEXwbPLkCjRJXtS_S-YoKC96aeHyRP0AttdbqUwXPoXteO2daj7-F_PjTak0L4b";
// $text = "Si mega chábr";
// $sendNotification = new SendNotification(); // Vytvoření instance třídy SendNotification
// $response = $sendNotification->sendNotification($token, $text);

class SendNotification{


    public function sendNotification($userToken, $signText): string
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
                    "title": "Please authorize for login",
                    "body": "' . $signText . '",
                },
                "data": {
                    "title": "Please authorize for login",
                    "body": "' . $signText . '"
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


