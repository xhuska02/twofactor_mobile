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

class SignatureVerifier{

    public function verifySignature($data, $signature, $publicKeyBase64) {
        $decodedSignature = base64_decode($signature);
        $publicKeyPEM = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($publicKeyBase64, 64, "\n") . "-----END PUBLIC KEY-----";
        $publicKeyResource = openssl_pkey_get_public($publicKeyPEM);
        if (!$publicKeyResource) {
            return false;
        }
        $result = openssl_verify($data, $decodedSignature, $publicKeyResource, OPENSSL_ALGO_SHA256);
        return ($result === 1);
    }
    

    public function deleteSignature($key) {
        if ($key === "123123123") {
            return true;
        } else {
            return false;
        }
    }

    public function stupidSignature($data, $signature, $publicKey) {
        if($data != null && $signature != null && $publicKey == "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEEZPXihGJJVtQ3iDV6+jxhB/xf2xuIYfO3/xw4oiJ2lDkcuiagrYY2La31Bn30dvXa2wN0WYhu4fojRs4ABuM3A=="){
            return true;
        } else {
            return false;
        }
    }

}