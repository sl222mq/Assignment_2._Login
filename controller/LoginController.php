<?php

class LoginController {
	private $view;
	private $model;

	function __construct(LoginView $loginView, LoginModel $loginModel) {
		$this->view 	= $loginView;
		$this->model 	= $loginModel;
	}

	function doLogin() {
		if($this->view->isPost()) {
			$loginCredentials = $this->view->getData();
			$message = $this->model->validate($loginCredentials);
			return $message;

		}
	}

	function isLoggedIn() {
		return false;
	}

}