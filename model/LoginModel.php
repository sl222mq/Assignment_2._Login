<?php

class LoginModel {
	private $username = "Admin";
	private $password = "dc647eb65e6711e155375218212b3964"; //md5'd "Password"
	public $postSuccess = false;

	// return message from session
	public function getMessage(){
		$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
		return $message;
	}

	public function clearMessage(){
		unset($_SESSION['message']);
	}

	// validate data from post and save message to session
	public function validate($data) {
		if($data["username"] == null) {
			$_SESSION['message'] = "Username is missing";
			return false;
		}
		if($data["password"] == null) {
			$_SESSION['message'] = "Password is missing";
			return false;
		}
		if($data["username"] != $this->username || $data["password"] != $this->password) {
			$_SESSION['message'] = "Wrong name or password";
			return false;
		}
		return true;
	}

	// validate data from cookies and save error message in session
	public function validateByCookies($data) {
		if($data["username"] != $this->username || $data["password"] != $this->password) {
			$_SESSION['message'] = "Wrong information in cookies";
			return false;
		}
		return true;
	}

	// set the session and save log in message in session
	public function setSession($data, $byCookie = false) {
		$this->postSuccess = true;
		$_SESSION['login'] = true;
		// save user web browser
		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		// save user IP
		$_SESSION['remote_addr'] = $_SERVER['REMOTE_ADDR'];
		if($byCookie){
			$_SESSION['message'] = "Welcome back with cookie";
		}else{
			$_SESSION['message'] = "Welcome";
		}
	}

	// unset session and save logout message in session
	public function unsetSession() {
		$this->postSuccess = true;
		unset($_SESSION['login']);
		$_SESSION['message'] = "Bye bye!";
	}

	// return true if user is logged in, else false
	public function isLoggedIn() {
		if(!isset($_SESSION['login'])){
			return false;
		}

		// check if user is hijacking
		if($_SERVER['HTTP_USER_AGENT'] != $_SESSION['user_agent'] || $_SERVER['REMOTE_ADDR'] != $_SESSION['remote_addr']){
			return false;
		}

		return true;
	}
}