<?php

require_once 'libraries/DB.php';

class Account {
	
	public const ID = 0;
	public const USERNAME = 1;
	public const EMAIL = 2;
	public const AVATAR = 3;

	public const FOLLOWING = 0;
	public const NOTFOLLOWING = 1;
	public const BLOCKED = 2;

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

	public static function getFollowers($userid) {
		$a = array();
		$data = DB::getData('followers', 'follower_id', 'user_id', $userid, 'blocked', '0');

		if ($data == NULL) return array();
		if (!is_array($data)) {
			array_push($a, $data);
			return $a;
		} else {
			return $data;
		}

		return NULL;
	}

	public static function getFollowing($userid) {
		$a = array();
		$data = DB::getData('followers', 'user_id', 'follower_id', $userid, 'blocked', '0');

		if ($data == NULL) return array();
		if (!is_array($data)) {
			array_push($a, $data);
			return $a;
		} else {
			return $data;
		}

		return NULL;
	}

	public static function followUser($userid) {
		$following = self::isFollowing($userid);
		$selfID = self::getID();
		if ($following != self::FOLLOWING && $following != self::BLOCKED) {
			DB::setData('followers', array('user_id'=>$userid, 'follower_id'=>$selfID, 'blocked'=>'0'), false);
		} else {
			DB::deleteData('followers', 'user_id', $userid, 'follower_id', $selfID);
		}
		return 0;
	}

	public static function blockUser($userid) {
		$following = self::isFollowing($userid);
		$selfID = self::getID();
		if ($following != self::BLOCKED) {
			if (DB::getData('followers', '', 'user_id', $userid, 'follower_id', $selfID) != NULL)
				DB::updateData('followers', array('blocked'=>'1'), 'user_id', $userid, 'follower_id', $selfID);
			else
				DB::setData('followers', array('user_id'=>$userid, 'follower_id'=>$selfID, 'blocked'=>'1'), false);
		} else {
			DB::deleteData('followers', 'user_id', $userid, 'follower_id', $selfID);
		}
	}

	public static function isFollowing($userid) {
		$selfID = self::getID();
		$userfollowed = DB::getData('followers', '', 'user_id', $userid, 'follower_id', $selfID);
		if ($userfollowed != NULL) 
			if ($userfollowed['blocked'] == 0)
				return self::FOLLOWING;
			else
				return self::BLOCKED;

		return self::NOTFOLLOWING;

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