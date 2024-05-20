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

use OCP\AppFramework\ApiController;
use OCP\AppFramework\Http\Attribute\CORS;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\JSONResponse;
use OCA\TwofactorMobile\Service\AplicationUserModel;
use OCP\IRequest;
use OCP\ISession;

class MobileApiController extends ApiController {

    private AplicationUserModel $aplicationUserModel;

    public function __construct($appName, IRequest $request, AplicationUserModel $aplicationUserModel) {
        parent::__construct($appName, $request);

        $this->aplicationUserModel = $aplicationUserModel;
    }

    #[CORS]
    public function index() {
        // Index metoda, můžete implementovat jakoukoliv požadovanou funkcionalitu
    }

    #[CORS]
    #[PublicPage]
    #[NoCSRFRequired]
    public function checkLogin($uid) {
        return new JSONResponse([
            $this->aplicationUserModel->getUserLoginState($uid)
        ], 200);
    }


    #[CORS]
    #[PublicPage]
    #[NoCSRFRequired]
    public function login($uid, $key) {
        $this->aplicationUserModel->setUserAllowLogin($uid, $key);

        // Návratová odpověď
        return new JSONResponse([
            'message' => 'Parametry uloženy.',
            'uid' => $uid,
            'key' => $key
        ],200);
    }


    #[PublicPage]
    #[NoCSRFRequired]
    public function test($uid) {
        return new JSONResponse([null], 200);
    }

    #[CORS]
    #[PublicPage]
    #[NoCSRFRequired]
    public function setDevice($matchingKey, $publicKey, $firebaseId, $login) {
        $this->aplicationUserModel->setUserDevice($matchingKey, $publicKey, $firebaseId, $login);
        // Návratová odpověď
        return new JSONResponse([
            'message' => 'Success'],200);
    }


}
