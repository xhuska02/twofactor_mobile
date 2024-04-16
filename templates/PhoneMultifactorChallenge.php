<?php
//script('twofactormobile', 'pooling');
//script('twofactormobile', 'test', array('type' => 'module'));
//script('twofactormobile', 'pooling', array('type' => 'module'));
script('twofactormobile', 'nevim', array('type' => 'module'));
?>
 
<p>Prosím potvrďte v aplikaci přihlášení</p><br>
<form method="POST" id="admin-2fa-form2">
    <input id="nonce-user" type="text" hidden="true" value="<?php p($_['nonce'][0]) ?>" readonly="readonly">
</form>
<form method="POST" id="admin-2fa-form">
    <input type="hidden" name="challenge" value="passmex">
</form>