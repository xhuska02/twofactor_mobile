<?php
namespace OCA\TwofactorMobile\Controller;

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use React\EventLoop\Loop;

require 'vendor/autoload.php';

class WebSocket {
    public function openWebServer() {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new YourWebSocketHandler()
                )
            ),
            8080
        );
        
        Loop::run($server);
    }


}
?>