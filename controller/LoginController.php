<?php

class LoginController {
	private $view;
	private $model;
	private $loggedIn = false;

	function __construct(LoginView $loginView, LoginModel $loginModel) {
		$this->view 	= $loginView;
		$this->model 	= $loginModel;
	}

	function doLogin() {
		if(!$this->model->isLoggedIn()){
			if($this->view->triedToLogin()){
				// get data from post
				$data = $this->view->getRequestData();
				
				if($this->model->validate($data)){	// validate post and set session and cookies
					$this->model->setSession($data);
					$this->view->setCookies($data);
				}
			}else if($this->view->hasLoginCookies()){	// create session if there are cookies
				$data = $this->view->getCookieData();
				if($this->model->validateByCookies($data)){
					$this->model->setSession($data, true);
				}
			}
		}else{
			if($this->view->triedToLogout()){ // logout by unsetting session and cookies
				$this->model->unsetSession();
				$this->view->unsetCookies();
			}
		}
	}
}