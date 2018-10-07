<?php

namespace view;


class View {
    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @param \model\LoginViewModel $viewModel
     * @return  void BUT writes to standard output and cookies!
     */

    public function render(\model\LoginViewModel $viewModel)
    {
        if(isset($_GET[\model\LoginConstants::getToRegister()])){
            return $this->generateRegisterUser(
                $viewModel->getMessage(),
                $viewModel->getUsername()
            );
        }

        if($viewModel->getIsLoggedIn()) {
            return $this->generateLogoutButtonHTML($viewModel->getMessage());
        }

        return $this->generateLoginFormHTML(
            $viewModel->getMessage(),
            $viewModel->getUsername());

    }


	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . \model\LoginConstants::getMessageId() . '">' . $message .'</p>
				<input type="submit" name="' . \model\LoginConstants::getLogout() . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message, $username) {
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

    /**
     *
     */
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
