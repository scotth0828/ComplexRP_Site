<?php

require_once 'libraries/DB.php';

class Account {
	
	public const ID = 0;
	public const USERNAME = 1;
	public const EMAIL = 2;
	public const AVATAR = 3;

	public static function verify($username, $password) {

		$data = DB::getData('accounts', 'Username', 'username', $username);
		if ($data != NULL) {
			$pass = DB::getData('accounts', 'Password', 'username', $username);
			if (password_verify($password, $pass))
				return true;
			return false;
		}

		return false;
	}

	public static function isLoggedIn() {
		$cookie = cookies::getCookie('SNID');
		if ($cookie != NULL) {
			$enct = self::encryptedToken($cookie);

			$query = DB::getData('logintokens', 'id', 'value', $enct);
			if ($query)
				return true;
		}
		return false;
	}

	public static function signOut() {
		$cookie = cookies::getCookie('SNID');
		if ($cookie != null) {
			$enct = self::encryptedToken($cookie);
			DB::deleteData('logintokens', 'value', $enct);
			cookies::removeCookie('SNID');
			return true;
		}
		return false;
	}

	public static function getData($type, $id = 0) {
		if ($id == 0)
			$id = self::getID();

		if ($type == self::ID) {
			return $id;
		}
		elseif ($type == self::USERNAME) {
			return DB::getData('accounts', 'Username', 'id', $id);
		}
		elseif ($type == self::EMAIL) {
			return DB::getData('accounts', 'Email', 'id', $id);
		}
		elseif ($type == self::AVATAR) {
			$a = DB::getData('accounts', 'Profileimg', 'id', $id);
			if ($a == NULL)
				return 'images/default_avatar.jpg';
			else
				return $a;
		}
	}

	public static function getIDFromType($type, $value) {
		if ($type == self::USERNAME)
			return DB::getData('accounts', 'id', 'Username', $value);
		elseif ($type == self::EMAIL)
			return DB::getData('accounts', 'id', 'Email', $value);
		return NULL;
	}

	public static function setVerified($id = 0) {
		if ($id == 0)
			$id = self::getID();

		DB::updateData('accounts', array('Verified'=>'1'), 'id', $id);
	}

	public static function createToken() {
		try {
			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			return $token;
		} catch(Exception $e) {
			return null;
		}
	}

	public static function createLoginCookies($userid) {
		try {
			$token = self::createToken();
			$encryptedToken = self::encryptedToken($token);
			cookies::setCookie('SNID', $token, cookies::TIME_HOUR);
			if (DB::getData('logintokens', 'id', 'user_id', $userid) != NULL) {
				DB::updateData('logintokens', array('value'=>$encryptedToken), 'user_id', $userid);
			} else {
				DB::setData('logintokens', array('value'=>$encryptedToken, 'user_id'=>$userid));
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	private function getID() {
		$cookie = cookies::getCookie('SNID');
		if ($cookie != NULL) {
			$enct = self::encryptedToken($cookie);
			return DB::getData('logintokens', 'user_id', 'value', $enct);
		}
		return 0;
	}

	private function encryptedToken($token) {
		try {
			$enc = sha1($token);
			return $enc;
		} catch(Exception $e) {
			return null;
		}
	}
}