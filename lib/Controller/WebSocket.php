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