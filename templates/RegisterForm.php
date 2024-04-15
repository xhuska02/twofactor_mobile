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
                <div class="infoLine">Stav: </div>
                <div class="infoLine">ID Firebase: <?php echo $firebaseId; ?></div>
                <div class="infoLine">publicKey: <?php echo $publicKey; ?></div>
            </div>
        </div>
        <!-- Display the button -->
        <button data-modal-target="#modal">Registrace nového zařízení</button>
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
                    <div class="qrCodeContainer" style="text-align:center; margin-top:20px;">
                        <img src="<?php echo htmlspecialchars($qrCodeDataUri); ?>" alt="QR Kód" style="width:200px; height:200px;">
                    </div>
                </div>
            </div>
            <div id="overlay"></div>

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
                </div>


            </div>
            <div id="overlay"></div>
    </myPageContent>
</body>

</html>
```