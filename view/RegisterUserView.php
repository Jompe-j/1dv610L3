<?php

namespace view;


class RegisterUserView
{
    private function generateRegisterUser($message, $username)
    {
        return ' 
			<form method="post" action="?"> 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="'.\model\LoginConstants::getRegisterMessage().'">' . $message . '</p>
					
					<label for="' . \model\LoginConstants::getRegisterName() . '">Username :</label>
					<input type="text" id="'.\model\LoginConstants::getRegisterName().'" name="' . \model\LoginConstants::getRegisterName() . '" value="' . $username . '" />

					<label for="' . \model\LoginConstants::getRegisterPassword() . '">Password :</label>
					<input type="password" id="'.\model\LoginConstants::getRegisterPassword().'" name="' . \model\LoginConstants::getRegisterPassword() . '" />
					
					<label for="' . \model\LoginConstants::getRegisterSamePassword() . '">Repeat password :</label>
					<input type="password" id="'.\model\LoginConstants::getRegisterSamePassword().'" name="' . \model\LoginConstants::getRegisterSamePassword() . '" />
					
					<input type="submit" name="' . \model\LoginConstants::getRegister() . '" value="register" />
				</fieldset>
			</form>
		';
    }
}