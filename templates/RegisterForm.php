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
 */?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>TwofactorMobile registration page</title>
    <?php
    style('twofactormobile', 'registerStyle');
    script('twofactormobile', 'registerFormLogic');
    ?>
</head>

<body>
    <myPageContent>
        <div class="myContainer">
            <div class="myHeader">
                <div class="infoLine">Aktuální uživatel: <?php echo $userID; ?></div>
                <div class="infoLine">Stav: <?php echo $isRegister === 'registrován' ? 'registrován' : 'nezaregistrován'; ?></div>
                <div class="infoLine">ID Firebase: <?php echo $firebaseId; ?></div>
                <div class="infoLine">publicKey: <?php echo $publicKey; ?></div>
            </div>
        </div>
        <!-- Display the button -->
        <button data-modal-target="#modal">Registrace nového zařízení</button>
        <div class="modal" id="modal">
            <div class="modal-header">
                <div class="title" style="text-align: center;">Postup registrace zařízení:</div>
            </div>
            <div class="modal-body">
                <p>Dobrý den, pro zpřístupnění dvoufaktorové autentizace prosím splňte následující body:</p>
                <p>1) Stáhněte si aplikaci do mobilního zařízení.</p>
                <p>2) V aplikaci načtěte záložku registrace a připravte si telefon k naskenování QR kódu.</p>
                <p>3) K naskenování kódu budete mít 60 vteřin.</p>
                <p>4) Po naskenování se prosím odhlašte a znovu přihlašte.</p>
                <div style="text-align: center; margin-top: 20px;">
                        <?php if($isRegister === 'registrován'): ?>
                            <div class="infoLine" style="color: red; font-weight: bold;">Již jste registrován</div>
                        <?php else: ?>
                    <div id="qrCodeContainer" class="qrCodeContainer" style="text-align:center; margin-top:20px; display:none;">
                        <img src="<?php echo htmlspecialchars($qrCodeDataUri); ?>" alt="QR Kód" style="width:200px; height:200px;">
                    </div>
                    <button id="qrButton">Generovat QR kód</button>
                        <?php endif; ?>
                </div>

            </div>
        </div>
        </div>
        <div id="overlay"></div>

        <!--
        <button data-modal-target="#modal">Zrušit dvoufázovou autentizaci</button>
        <div class="modal" id="modal">
            <div class="modal-header">
                <div class="title">Postup registrace zařízení:</div>
                <button data-close-button class="close-button">&times;</button>
            </div>
            <div class="modal-body">
                <p>Dobrý den, pro zpřístupnění dvoufaktorové autentizace prosím splňte následující body:</p>
                <p>1) Stáhněte si aplikaci do mobilního zařízení.</p>
                <p>2) V aplikaci načtěte záložku registrace a připravte si telefon k naskenování QR kódu.</p>
                <p>3) K naskenování kódu budete mít 60 vteřin.</p>
                <p>4) Po naskenování se prosím odhlašte a znovu přihlašte.</p>
            </div>-->


        </div>
        <div id="overlay"></div>
    </myPageContent>
</body>

</html>
```