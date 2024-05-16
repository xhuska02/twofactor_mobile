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

namespace OCA\TwofactorMobile\Controller;

	use OCA\TwofactorMobile\AppInfo\Application;
	use OCP\AppFramework\Controller;
	use OCP\AppFramework\Http\TemplateResponse;
	use OCP\IUserManager;
	use OCP\Util;
	use OCP\IRequest;
	use Endroid\QrCode\QrCode;
	use Endroid\QrCode\Writer\PngWriter;
	use Endroid\QrCode\Encoding\Encoding;
	use OCA\TwofactorMobile\Service\AplicationUserModel;
	use OCP\IUser;
	use OCP\IUserSession;

	class PageController extends Controller {

		private AplicationUserModel $aplicationUserModel;
		private IUser $user;
		private string $secretCode;

		public function __construct(IRequest $request, AplicationUserModel $aplicationUserModel, IUserSession $userSession) {
			parent::__construct(Application::APP_ID, $request);
			$this->aplicationUserModel = $aplicationUserModel;
			$this->user = $userSession->getUser();
			$this->secretCode = $this->aplicationUserModel->getUserMatchingKey($this->user);
		}
		/**
		 * @NoAdminRequired
		 * @NoCSRFRequired
		 */
		public function index(): TemplateResponse {
			Util::addScript(Application::APP_ID, 'twofactormobile-main');

			$data = [
				'secretCode' => $this->secretCode,
				'user' => $this->user->getUID()
			];

			// Vytvoření QR kódu
			$qrCode = QrCode::create(json_encode($data))
				->setSize(300)
				->setMargin(10)
				->setEncoding(new Encoding('UTF-8'));
			
			// Vytvoření writeru
			$writer = new PngWriter();
			
			// Generování Data URI pro QR kód
			$dataUri = $writer->write($qrCode)->getDataUri();


			$parameters = array(
				'pageTitle' => 'Registrace nového zařízení',
				'userID' => $this->user->getDisplayName(),
				'qrCodeDataUri' => $dataUri,
				'publicKey' => $this->aplicationUserModel->getUserMobileParam($this->user, AplicationUserModel::PUBLIC_KEY),
				'firebaseId' => $this->aplicationUserModel->getUserMobileParam($this->user, AplicationUserModel::FIREBASE_ID),
				'isRegister' => $this->aplicationUserModel->getUserMobileParam($this->user, AplicationUserModel::USER_REGISTERED),
			);

			return new TemplateResponse(Application::APP_ID, 'RegisterForm', $parameters);
			
		}
	}
