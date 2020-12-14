<?php

class cookies {

	public const TIME_HOUR = 3600;
	public const TIME_DAY = cookies::TIME_HOUR * 24;
	public const TIME_MONTH = cookies::TIME_DAY * 30;
	public const TIME_YEAR = cookies::TIME_MONTH * 12;

	public static function setCookie($name, $value, $time) {
		try {
			setcookie($name, $value, time() + $time, "/");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function getCookie($name) {
		if (self::isValid($name)) 
			return $_COOKIE[$name];
		return NULL;
	}

	public static function removeCookie($name) {
		if (self::isValid($name)) {
			self::setCookie($name, "", time() - self::TIME_HOUR);
			return true;
		}
		return false;
	}

	public static function isCookieEnabled() {
		self::setCookie("test_cookie", "test", self::TIME_HOUR);
		if(count($_COOKIE) > 0) {
			return true;
		}
		return false;
	}

	public static function isValid($name) {
		if (isset($_COOKIE[$name]))
			return true;
		return false;
	}
}