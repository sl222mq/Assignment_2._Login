<?php
session_start();

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('model/LoginModel.php');

// show php errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// creates instances of classes
$loginModel 		= new LoginModel();
$loginView 			= new LoginView($loginModel);
$dateTimeView 		= new DateTimeView();
$layoutView 		= new LayoutView();
$loginController 	= new LoginController($loginView, $loginModel);

$loginController->doLogin();

$isLoggedIn = $loginModel->isLoggedIn();

$layoutView->render($isLoggedIn, $loginView, $dateTimeView);

