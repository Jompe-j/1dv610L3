<?php
session_start();

//INCLUDE THE FILES NEEDED...
require_once('view/View.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/Controller.php');
require_once ('model/LoginDbModel.php');
require_once('model/LoginCredentialsModel.php');
require_once ('settings/DbSettings.php');
require_once ('model/LoginViewModel.php');
require_once('model/LoginModel.php');
require_once ('model/LoginConstants.php');
require_once ('model/LoginAttempt.php');
require_once ('model/TokenModel.php');
require_once ('model/RegisterModel.php');
require_once ('model/RegisterCredentialsModel.php');
require_once ('model/RegisterAttempt.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');
//CREATE CONTROLLERS
$Controller = new \controller\Controller(new model\LoginModel(), new model\RegisterModel());
//$LoginController = new \controller\LoginController(new model\LoginModel);


//CREATE OBJECTS OF THE VIEWS
$dateTimeView = new \view\DateTimeView();
$layoutView = new \view\LayoutView();

$layoutView->render($Controller->createLoginViewModel(), $dateTimeView);




