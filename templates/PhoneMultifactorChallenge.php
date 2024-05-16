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

style('twofactormobile', 'loginStyle');
script('twofactormobile', 'login', array('type' => 'module'));
?>
 
 <div class="confirmation-message">
    <p>Prosím potvrďte v aplikaci příchozí požadavek na přihlášení.</p>
</div><br>
<form method="POST" id="admin-2fa-form2">
    <input id="nonce-user" type="text" hidden="true" value="<?php p($_['nonce'][0]) ?>" readonly="readonly">
</form>
<form method="POST" id="admin-2fa-form">
    <input id="hesloprokokoty" type="hidden" name="challenge" value="">
</form>