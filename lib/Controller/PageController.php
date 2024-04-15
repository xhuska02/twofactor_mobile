<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Luděk Huška <xhuska02@vutbr.cz>
// SPDX-License-Identifier: AGPL-3.0-or-later

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
				'userID' => $this->user->getDisplayName() . ' je kunda' . $this->secretCode,
				'qrCodeDataUri' => $dataUri,
				'publicKey' => $this->aplicationUserModel->getUserMobileParam($this->user, AplicationUserModel::PUBLICKEY),
				'firebaseId' => $this->aplicationUserModel->getUserMobileParam($this->user, AplicationUserModel::FIREBASE_ID)
			);

			return new TemplateResponse(Application::APP_ID, 'RegisterForm', $parameters);
			
		}
	}
