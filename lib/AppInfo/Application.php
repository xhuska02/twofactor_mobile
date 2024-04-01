<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Luděk Huška <xhuska02@vutbr.cz>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\TwofactorMobile\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'twofactormobile';
	public const SUBJECT_APP_TOKEN_ID = 'fXQTOd8_T2e63Y9zqNarv5:APA91bEJiNih5dc0wz5-JC9NYDLr-afPzAYvarXo-2a7XLAKVvEI_J_UKSD2kDDyfDLjcn_fvm0Q1nEXwbPLkCjRJXtS_S-YoKC96aeHyRP0AttdbqUwXPoXteO2daj7-F_PjTak0L4b';
	public const PUBLIC_KEY_OF_USER = '';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}


	public function register(IRegistrationContext $context): void {
	    require_once __DIR__.'/../../vendor/autoload.php';
		/*
		 * For further information about the app bootstrapping, please refer to our documentation:
		 * https://docs.nextcloud.com/server/latest/developer_manual/app_development/bootstrap.html
		 */
		// Register your services, event listeners, etc.
	}

	public function boot(IBootContext $context): void {
		/*
		 * For further information about the app bootstrapping, please refer to our documentation:
		 * https://docs.nextcloud.com/server/latest/developer_manual/app_development/bootstrap.html
		 */
		// Prepare your app.
	}
}
