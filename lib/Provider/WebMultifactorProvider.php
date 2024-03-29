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
use OCP\Authentication\TwoFactorAuth\IProvidesIcons;
use OCP\Authentication\TwoFactorAuth\IRegistry;
use OCA\TwofactorMobile\AppInfo\Application;
use OCA\TwofactorMobile\Service\MultifactorLogic;
use OCA\TwofactorMobile\Service\SendNotification;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Template;
use Psr\Log\LoggerInterface;

class WebMultifactorProvider implements IProvider, IActivatableByAdmin, IDeactivatableByAdmin
{
    /** @var LoggerInterface */
    	private $logger;

    	/** @var IURLGenerator */
    	private $urlGenerator;

    	/** @var IRegistry */
    	private $registry;

    public function __construct(
            LoggerInterface $logger,
            IURLGenerator $urlGenerator,
            IRegistry $registry
        ) {
            $this->logger = $logger;
            $this->urlGenerator = $urlGenerator;
            $this->registry = $registry;
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
            return 'This enables second authentication factor using Mobile APp.';
        }

    public function getTemplate(IUser $user): Template
        {

            $token = "fXQTOd8_T2e63Y9zqNarv5:APA91bEJiNih5dc0wz5-JC9NYDLr-afPzAYvarXo-2a7XLAKVvEI_J_UKSD2kDDyfDLjcn_fvm0Q1nEXwbPLkCjRJXtS_S-YoKC96aeHyRP0AttdbqUwXPoXteO2daj7-F_PjTak0L4b";
            $text = "Si mega chábr";
            $sendNotification = new SendNotification(); // Vytvoření instance třídy SendNotification
            $response = $sendNotification->sendNotification($token, $text);
            echo "Odpověď je: " . $response;
            
            $template = new Template(Application::APP_ID, 'PhoneMultifactorChallenge');


		    return $template;
        }

    public function verifyChallenge(IUser $user, $challenge): bool
        {
    
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