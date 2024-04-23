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
use OC\Authentication\TwoFactorAuth\ProviderManager;
use OCP\IUser;
use OCP\IConfig;
use OCP\IUserSession;
use OCP\IUserManager;

class AplicationUserModel{

    const DEVICE_ID = "deviceID";
	const PUBLIC_USER_KEY = "userKey";
    const DEVICE_MATCH_KEY = "matchKey";
    const FIREBASE_ID = "firebaseId";
    const PUBLIC_KEY = "publicKey";
    const SIGN_TEXT_CHALLENGE = "signTextChallenge";
    const USER_REGISTERED = "userRegistered";


	/** @var IConfig */
	private $config;

    /** @var ProviderManager */
	private $providerManager;

     /** @var SignatureVerifier */    
     private $signatureVerifier;

     /** @var IUserManager */    
     private $UserManager;

	public function __construct(
		IConfig $config,
        SignatureVerifier $signatureVerifier,
        ProviderManager $providerManager,
        IUserManager $UserManager,
	) {
		$this->config = $config;
        $this->signatureVerifier = $signatureVerifier;
        $this->providerManager = $providerManager;
        $this->UserManager = $UserManager;
	}

    public function setSignTextChallenge(IUser $user) : string 
    {
        $challenge = bin2hex(random_bytes(10)); 

        $this->config->setUserValue(
            $user->getUID(),
            Application::APP_ID,
            self::SIGN_TEXT_CHALLENGE,
            $challenge
        );
        return $challenge;
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

    public function setUserAllowLogin(string $uid, string $key) : void {

        if($key === null) {
            return;
        }

        $pubKey = $this->config->getUserValue(
            $uid,
            Application::APP_ID,
            self::PUBLIC_KEY
        );

        //Nejprve získám challenge, který následně podepíšu
        $challenge = $this->config->getUserValue(
            $uid,
            Application::APP_ID,
            self::SIGN_TEXT_CHALLENGE
        );

        //$isvalid = $this->signatureVerifier->deleteSignature($key);
        $isvalid = $this->signatureVerifier->verifySignature($challenge, $key, $pubKey);
        
        // Nastavení SIGN_TEXT_CHALLENGE na prázdný řetězec ať podpis nejde znovu použít.
        $this->config->setUserValue(
            $uid,
            Application::APP_ID,
            self::SIGN_TEXT_CHALLENGE,
            ""
        );

        $this->config->setAppValue(
            Application::APP_ID,
            $uid,
            $isvalid === true ? true : false // todo kontrola
        );

    }

    /*
    Zpracování požadavku při registraci. Po spuštění funkce se zavolá checkUserMatchingKey. Po návratu true se nastaví PUBLICKEY a FIREBASE_ID. Zaregistruje se uživatel a nastaví se registrace na stav "registrovan"
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
            $this->registerUser($login);
            $this->setUserRegister($login);
        }
    }

    public function allowUserLogin(string $uid) : bool {

        $allowLogin = (bool) $this->config->getAppValue(
            Application::APP_ID,
            $uid,
        );
        $this->config->deleteAppValue(Application::APP_ID, $uid);
        return $allowLogin;
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

    public function registerUser($login) : void {
        $userId = $this->UserManager->get($login);
        $this->providerManager->tryEnableProviderFor("twofactormobile" , $userId);
    }

    public function setUserRegister(string $login) : void
    {
        $this->config->setUserValue(
            $login,
            Application::APP_ID,
            self::USER_REGISTERED,
            "registrován"
        );
    }

	public function getUserMobileParam(IUser $user, string $Key) : string
    {
        return $this->config->getUserValue(
			$user->getUID(),
            Application::APP_ID,
            $Key
        );
    }

    public function getUserLoginState(string $uid) : bool {

        $allowLogin = (bool) $this->config->getAppValue(
            Application::APP_ID,
            $uid,
        );
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
    
}