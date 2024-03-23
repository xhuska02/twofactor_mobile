<?php
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

require '../vendor/autoload.php';

$credential = new ServiceAccountCredentials(
    "https://www.googleapis.com/auth/firebase.messaging",
    json_decode(file_get_contents("../mob.json"), true)
);

$token = $credential->fetchAuthToken(HttpHandlerFactory::build());

$ch = curl_init("https://fcm.googleapis.com/v1/projects/mobiletwofactordect/messages:send");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$token['access_token']
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, '{
    "message": {
      "token": "fXQTOd8_T2e63Y9zqNarv5:APA91bEJiNih5dc0wz5-JC9NYDLr-afPzAYvarXo-2a7XLAKVvEI_J_UKSD2kDDyfDLjcn_fvm0Q1nEXwbPLkCjRJXtS_S-YoKC96aeHyRP0AttdbqUwXPoXteO2daj7-F_PjTak0L4b",
      "notification": {
        "title": "Background Message Title",
        "body": "Background message body",
        "image": "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024"
      },
      "webpush": {
        "fcm_options": {
          "link": "https://google.com"
        }
      }
    }
  }');

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

  $response = curl_exec($ch);

  curl_close($ch);

  echo $response;