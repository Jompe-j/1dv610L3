<?php

namespace view;


class LoginFormView
{


    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    public function generateLoginFormHTML($message, $username) {
        return '
			<form method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . \model\LoginConstants::getMessageId() . '">' . $message . '</p>
					
					<label for="' . \model\LoginConstants::getName() . '">Username :</label>
					<input type="text" id="' . \model\LoginConstants::getName() . '" name="' . \model\LoginConstants::getName() . '" value="' . $username . '" />

					<label for="' . \model\LoginConstants::getPassword() . '">Password :</label>
					<input type="password" id="' . \model\LoginConstants::getPassword() . '" name="' . \model\LoginConstants::getPassword() . '" />

					<label for="' . \model\LoginConstants::getKeep() . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . \model\LoginConstants::getKeep() . '" name="' . \model\LoginConstants::getKeep() . '" />
					
					<input type="submit" name="' . \model\LoginConstants::getLogin() . '" value="login" />
				</fieldset>
			</form>
		';
    }



}