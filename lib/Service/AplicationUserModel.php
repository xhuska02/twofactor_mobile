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

use OCA\TwofactorMobile\AppInfo\Application;
use OCP\IUser;
use OCP\IConfig;


class AplicationUserModel{

    const DEVICE_ID = "deviceID";
	const PUBLIC_USER_KEY = "userKey";

	/** @var IConfig */
	private $config;

	public function __construct(
		IConfig $config
	) {
		$this->config = $config;
	}

    public function setUserMobileParam(string $token, IUser $user, string $Key):void
    {
        $this->config->setUserValue(
			$user->getUID(),
            Application::APP_ID,
            $Key,
			$token
        );
    }

	public function getUserMobileParam(IUser $user, string $Key):string
    {

        return $this->config->getUserValue(
			$user->getUID(),
            Application::APP_ID,
            $Key
        );
    }

    public function setUserAllowLogin(string $uid, string $key) : void {

        if($key === null) {
            return;
        }


        $this->config->setAppValue(
            Application::APP_ID,
            $uid,
            $key === "123123123" ? true : false // todo kontrola
        );
    }
    
    public function allowUserLogin(string $uid) : bool {

        $allowLogin = (bool) $this->config->getAppValue(
            Application::APP_ID,
            $uid,
        );
        $this->config->deleteAppValue(Application::APP_ID, $uid);
        return $allowLogin;
    }
}