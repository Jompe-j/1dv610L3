<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-07
 * Time: 20:29
 */

namespace view;


class LoggedInView
{
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

}