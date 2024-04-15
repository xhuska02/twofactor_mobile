<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Luděk Huška <xhuska02@vutbr.cz>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\TwofactorMobile\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'resources' => [
		'note' => ['url' => '/notes'],
		'note_api' => ['url' => '/api/0.1/notes']
	],
	'routes' => [
        ['name' => 'page#index', 'url' => '/api/1.0', 'verb' => 'GET'],
        ['name' => 'mobile_api#hello', 'url' => '/api/1.0/hello', 'verb' => 'POST', 'protected' => false],
        ['name' => 'mobile_api#foo', 'url' => '/api/1.0/foo', 'verb' => 'POST', 'protected' => false],
		['name' => 'mobile_api#setDevice', 'url' => '/api/1.0/set-device', 'verb' => 'POST', 'protected' => false],
		['name' => 'mobile_api#test', 'url' => '/api/1.0/test', 'verb' => 'GET','protected' => false],
	]
];
