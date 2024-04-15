<?php
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

    #[PublicPage]
    #[NoCSRFRequired]
    public function hello($uid) {
        return new JSONResponse([
            $this->aplicationUserModel->getUserLoginState($uid)
        ], 200);
    }


    #[CORS]
    #[PublicPage]
    #[NoCSRFRequired]
    public function foo($uid, $key) {
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
