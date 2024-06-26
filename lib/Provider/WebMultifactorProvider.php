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
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace OCA\TwofactorMobile\Provider;


use OCP\Authentication\TwoFactorAuth\IActivatableByAdmin;
use OCP\Authentication\TwoFactorAuth\IDeactivatableByAdmin;
use OCP\Authentication\TwoFactorAuth\IProvider;
use OCP\Authentication\TwoFactorAuth\IRegistry;
use OCA\TwofactorMobile\AppInfo\Application;
use OCA\TwofactorMobile\Service\AplicationUserModel;
use OCA\TwofactorMobile\Service\SendNotification;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Template;
use Psr\Log\LoggerInterface;

class WebMultifactorProvider implements IProvider, IActivatableByAdmin, IDeactivatableByAdmin
{

    /** @var LoggerInterface */
    	private $logger;

    /** @var AplicationUserModel */    
        private $aplicationUserModel;

    	/** @var IURLGenerator */
    	private $urlGenerator;

    	/** @var IRegistry */
    	private $registry;


    public function __construct(
            LoggerInterface $logger,
            IURLGenerator $urlGenerator,
            IRegistry $registry,
            AplicationUserModel $aplicationUserModel
        ) {
            $this->logger = $logger;
            $this->urlGenerator = $urlGenerator;
            $this->registry = $registry;
            $this->aplicationUserModel = $aplicationUserModel;
        }

    public function getId(): string
        {
            return Application::APP_ID;
        }

    public function getDisplayName(): string
        {
            return 'Mobile Multifactor';
        }

    public function getDescription(): string
        {
            return 'This enables second authentication factor using Mobile APP.';
        }

    public function getTemplate(IUser $user): Template
        {    
            $text = $user->getUID();
            $challenge = $this->aplicationUserModel->setSignTextChallenge($user);
            $this->aplicationUserModel->setUserMobileParam($text ,$user, AplicationUserModel::PUBLIC_USER_KEY);
            $token=$this->aplicationUserModel->getUserMobileParam($user, AplicationUserModel::FIREBASE_ID);
            $sendNotification = new SendNotification(); // Vytvoření instance třídy SendNotification
            $response = $sendNotification->sendNotification($token, $text, $challenge);

            $template = new Template(Application::APP_ID, 'PhoneMultifactorChallenge');
            $template->append('nonce', $text);


		    return $template;
        }

    public function verifyChallenge(IUser $user, $challenge): bool
        {
            if ($this->aplicationUserModel->allowUserLogin($user->getUID())) {
                return true;
            }
            return false;
        }
    
    public function isTwoFactorAuthEnabledForUser(IUser $user): bool
        {
            $providerStates = $this->registry->getProviderStates($user);
    
            return array_key_exists(Application::APP_ID, $providerStates)
                ? boolval($providerStates[Application::APP_ID])
                : false;
        }

    public function enableFor(IUser $user)
    	{
    		$this->registry->enableProviderFor($this, $user);
    	}

    public function disableFor(IUser $user)
    	{
    		$this->registry->enableProviderFor($this, $user);
    	}

        

}