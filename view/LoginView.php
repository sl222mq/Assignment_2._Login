<?php

class LoginView {
	private static $login 			= 'LoginView::Login';
	private static $logout 			= 'LoginView::Logout';
	private static $name 			= 'LoginView::UserName';
	private static $password 		= 'LoginView::Password';
	private static $cookieName 		= 'LoginView::CookieName';
	private static $cookiePassword 	= 'LoginView::CookiePassword';
	private static $keep 			= 'LoginView::KeepMeLoggedIn';
	private static $messageId 		= 'LoginView::Message';
	private $model 					= null;


	public function __construct(LoginModel $loginModel)
	{
		$this->model = $loginModel;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$response = "";
		$message = $this->model->getMessage();

		if(!empty($_POST) && $this->model->postSuccess){
			// make it look right...
			header("HTTP/1.1 302 Found");
			// refresh the page
			header("Location: ".$_SERVER['PHP_SELF']);
			exit;
		}

		// clear message from session so it doesnt show more than one time
		$this->model->clearMessage();
		
		if($this->model->isLoggedIn()) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message);
		}

		return $response;
	}

	// set cookies if the user wants to be remembered, otherwise set cookies to "deleted" 
	public function setCookies() {
		if($this->getRequestKeep()) {
			$date = new Datetime('+2 hours');
			setcookie(self::$cookieName, $this->getRequestUserName(),  time()+6200, "/"); // 1 day
			setcookie(self::$cookiePassword, $this->getRequestPassword(),  time()+6200, "/"); // 1 day
		}else{
			setcookie(self::$cookieName, "deleted", 1);
			setcookie(self::$cookiePassword, "deleted", 1);
		}
	}

	public function unsetCookies() {
		// remove username cookie 
		if(isset($_COOKIE[self::$cookieName])) {
			unset($_COOKIE[self::$cookieName]);
			// set the cookie to empty and time to an expired time to remove cookie completely
			setcookie(self::$cookieName, '', time() - 3600, '/');
		}

		//remove password cookie
		if(isset($_COOKIE[self::$cookiePassword])) {
			unset($_COOKIE[self::$cookiePassword]);
			// set the cookie to empty and time to an expired time to remove cookie completely
			setcookie(self::$cookiePassword, '', time() - 3600, '/');
		}
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return "
			<form method='post' > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id='".self::$messageId."'>".$message."</p>

					<label for='".self::$name."'>Username :</label>
					<input type='text' id='" . self::$name . "' name='" . self::$name . "' value='".$this->getRequestUserName()."' />

					<label for='" . self::$password . "'>Password :</label>
					<input type='password' id='" . self::$password . "' name='" . self::$password . "' />

					<label for='" . self::$keep . "'>Keep me logged in  :</label>
					<input type='checkbox' id='" . self::$keep . "' name='" . self::$keep . "' ". $this->getChecked(self::$keep) ." />
					
					<input type='submit' name='" . self::$login . "' value='login' />
				</fieldset>
			</form>
		"; 
	}

	private function getRequestUserName() {
		return (isset($_POST[self::$name]) && $_POST[self::$name] != "") ? $_POST[self::$name] : null;
	}

	private function getRequestPassword() {
		return ($_POST[self::$password] != "") ? md5($_POST[self::$password]) : null;
	}

	public function getRequestKeep() {
		return isset($_POST[self::$keep]) ? true : false;
	}

	//only for returning "checked" or nothing to remember "keep me logged in"-status after failed login attempt
	private function getChecked($key) {
		return isset($_POST[$key]) ? "checked" : "";
	}

	private function getCookieUserName() {
		return isset($_COOKIE[self::$cookieName]) ? $_COOKIE[self::$cookieName] : null;
	}

	private function getCookiePassword() {
		return isset($_COOKIE[self::$cookiePassword]) ? $_COOKIE[self::$cookiePassword] : null;
	}

	public function hasLoginCookies()
	{
		return (isset($_COOKIE[self::$cookiePassword]) && isset($_COOKIE[self::$cookieName]));
	}

	public function triedToLogin() {
		if (empty($_POST)) return false;

		return isset($_POST[self::$login]);
	}

	public function triedToLogout() {
		if (empty($_POST)) return false;

		return isset($_POST[self::$logout]);
	}

	public function getRequestData() {
		return array("username" => $this->getRequestUserName(),
					 "password" => $this->getRequestPassword(),
					 "keep" 	=> $this->getRequestKeep()
					 );
	}

	public function getCookieData() {
		return array("username" => $this->getCookieUserName(),
					 "password" => $this->getCookiePassword(),
					 "keep" 	=> true //keep this true
					 );
	}
}