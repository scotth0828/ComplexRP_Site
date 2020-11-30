<?php

include_once 'DB.php';

class cookies {

	public $TIME_HOUR, $TIME_DAY, $TIME_MONTH, $TIME_YEAR;

	public function __construct() {
		$this->TIME_HOUR = 3600;
		$this->TIME_DAY = $this->TIME_HOUR * 24;
		$this->TIME_MONTH = $this->TIME_DAY * 30;
		$this->TIME_YEAR = $this->TIME_MONTH * 12;
	}

	public function setCookie($name, $value, $time) {
		try {
			setcookie($name, $value, time() + $time, "/");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function getCookie($name) {
		if ($this->isValid($name)) 
			return $_COOKIE[$name];
		return NULL;
	}

	public function removeCookie($name) {
		if ($this->isValid($name)) {
			setcookie($name, "", time() - $this->TIME_HOUR, "/");
			return true;
		}
		return false;
	}

	public function isCookieEnabled() {
		setCookie("test_cookie", "test", $this->TIME_HOUR, "/");
		if(count($_COOKIE) > 0) {
			return true;
		}
		return false;
	}

	public function isValid($name) {
		if (isset($_COOKIE[$name]))
			return true;
		return false;
	}
}