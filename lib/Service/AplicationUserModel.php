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
use OCA\TwofactorMobile\Service\SignatureVerifier;
use OCP\Authentication\TwoFactorAuth\IRegistry;
use OC\Authentication\TwoFactorAuth\ProviderManager;
use OCP\IUser;
use OCP\IConfig;
use OCP\IUserSession;

class AplicationUserModel{

    const DEVICE_ID = "deviceID";
	const PUBLIC_USER_KEY = "userKey";
    const DEVICE_MATCH_KEY = "matchKey";
    const FIREBASE_ID = "firebaseId";
    const PUBLIC_KEY = "publicKey";


	/** @var IConfig */
	private $config;

    /** @var ProviderManager */
	private $providerManager;

     /** @var SignatureVerifier */    
     private $signatureVerifier;

     private IUserSession $userSession;

	public function __construct(
		IConfig $config,
        SignatureVerifier $signatureVerifier,
        ProviderManager $providerManager,
        IUserSession $userSession
	) {
		$this->config = $config;
        $this->signatureVerifier = $signatureVerifier;
        $this->providerManager = $providerManager;
        $this->userSession = $userSession;
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

        $pubKey = $this->config->getUserValue(
            $uid,
            Application::APP_ID,
            self::PUBLIC_KEY
        );

        //$isvalid = $this->signatureVerifier->deleteSignature($key);
        $isvalid = $this->signatureVerifier->verifySignature("123123123", $key, $pubKey);

        $this->config->setAppValue(
            Application::APP_ID,
            $uid,
            $isvalid === true ? true : false // todo kontrola
        );

    }

    public function getUserLoginState(string $uid) : bool {

        $allowLogin = (bool) $this->config->getAppValue(
            Application::APP_ID,
            $uid,
        );
        return $allowLogin;
    }
    
    public function allowUserLogin(string $uid) : bool {

        $allowLogin = (bool) $this->config->getAppValue(
            Application::APP_ID,
            $uid,
        );
        $this->config->deleteAppValue(Application::APP_ID, $uid);
        return $allowLogin;
    }

    public function getUserMatchingKey(IUser $user) : string {

        //$this->config->deleteUserValue($user->getUID(), Application::APP_ID, self::DEVICE_MATCH_KEY);

        $matchKey = $this->config->getUserValue(
			$user->getUID(),
            Application::APP_ID,
            self::DEVICE_MATCH_KEY
        );
        if($matchKey === "") {
            return $this->generateMatchingKey($user);
        }
        return $matchKey;
    }


    /*
    Funkce pro porovnání přijatého klíče s aktuálním uživatelovým.
    */
    public function checkUserMatchingKey($loginUID, $mKey) : bool {
        $userMatchKey = $this->config->getUserValue(
            $loginUID,
            Application::APP_ID,
            self::DEVICE_MATCH_KEY
        );
        return $mKey === $userMatchKey;
    }

    private function generateMatchingKey(IUser $user): string {
        $userId = $user->getUID();
        $key = bin2hex(random_bytes(25));
        //$key = hash('sha256', $userId . $value);
        $this->config->setUserValue(
                $user->getUID(),
                Application::APP_ID,
                self::DEVICE_MATCH_KEY,
                $key
            );
        $this->config->setAppValue(
                Application::APP_ID,
                self::DEVICE_MATCH_KEY . $key,
                $user->getUID()
            );

        return $key;
    }

    /*
    Zpracování požadavku při registraci. Po spuštění funkce se zavolá checkUserMatchingKey. Po návratu true se nastaví PUBLICKEY a FIREBASE_ID
    */
    public function setUserDevice(string $matchingKey, string $publicKey, string $firebaseId, string $login) : void {
        
        if ($this->checkUserMatchingKey($login, $matchingKey)) {
            $this->config->setUserValue(
                $login,
                Application::APP_ID,
                self::PUBLIC_KEY,
                $publicKey
            );
    
            $this->config->setUserValue(
                $login,
                Application::APP_ID,
                self::FIREBASE_ID,
                $firebaseId
            );
        }
    }

    public function registerUser(string $login) : void {

        $user = $this->userSession->getUser();
        $this->providerManager->tryEnableProviderFor("twofactormobile", $user);
    }
    
}