<?php
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('model/LoginModel.php');

// show php errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// creates instances of classes
$loginView 			= new LoginView();
$dateTimeView 		= new DateTimeView();
$layoutView 		= new LayoutView();
$loginModel 		= new LoginModel();
$loginController 	= new LoginController($loginView, $loginModel);


$message = $loginController->doLogin();

$isLoggedIn = $loginController->isLoggedIn();

$layoutView->render($isLoggedIn, $loginView, $dateTimeView, $message);

