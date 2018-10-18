<?php
session_start();

//INCLUDE THE FILES NEEDED...
require_once ('model/ICredentialsModel.php');
require_once ('view/IContentView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once ('controller/CalculatorController.php');
require_once('controller/Controller.php');
require_once ('model/LoginDbModel.php');
require_once('model/LoginCredentialsModel.php');
require_once ('settings/DbSettings.php');
// require_once ('model/LoginViewModel.php');
require_once('model/LoginModel.php');
require_once ('model/LoginConstants.php');
//require_once ('model/LoginAttempt.php');
require_once ('model/TokenModel.php');
require_once ('model/RegisterModel.php');
require_once ('model/RegisterCredentialsModel.php');
//require_once ('model/RegisterAttempt.php');
require_once ('model/CalculatorModel.php');
require_once ('view/LoginFormView.php');
require_once ('controller/LoginController.php');
require_once ('view/CalculatorView.php');
require_once ('model/UsernameModel.php');
require_once ('model/PasswordModel.php');
require_once ('model/CookieSettingsModel.php');
require_once ('controller/RegisterController.php');
require_once ('view/RegisterFormView.php');
require_once ('view/RegisterCredentialsValidator.php');



//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//$LoginController = new \controller\LoginController(new model\LoginModel);


//CREATE OBJECTS OF THE VIEWS
$dateTimeView = new \view\DateTimeView();
$layoutView = new \view\LayoutView();
// $view = new \view\View($layoutView);

//CREATE CONTROLLERS
$controller = new \controller\Controller($layoutView);
try{
    $controller->checkViewState();
} catch (\Exception $exception){
    echo $exception;
}






