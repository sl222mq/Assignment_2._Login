<?php

class LoginModel {
	private $username = "Admin";
	private $password = "Password";

	public function validate($data) {
		if(empty($data["username"])) {
			return "Username is missing";
		}
		if(empty($data["password"])) {
			return "Password is missing";
		}
		if($data["username"] != $this->username || $data["password"] != $this->password) {
			return "Wrong username or password";
		}
	}
}