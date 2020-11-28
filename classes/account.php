<?php 

include 'classes/DB.php';
include 'classes/cookies.php';

class account {

	protected $DB;
	protected $cookies;

	public function __construct() {
		$this->DB = new DB();
		$this->cookies = new cookies();
	}

	function verify($username, $password) {
		if ($this->DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
			$query = $this->DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username));
			if (password_verify($password, $query[0]['password']))
				return true;
			return false;
		}
		return false;
	}

	function isLoggedIn() {
		$cookie = $this->cookies->getCookie('SNID');
		if ($cookie != NULL) { 
			$enct = $this->encryptedToken($cookie);
			$query = $this->DB::query('SELECT id FROM logintokens WHERE value=:token LIMIT 1', array(':token'=>$enct));	
			if ($query)
				return true;
		}
		return false;
	}

	function signOut() {
		$cookie = $this->cookies->getCookie('SNID');
		if ($cookie != null) {
			$enct = $this->encryptedToken($cookie);
			$this->DB::query('DELETE FROM logintokens WHERE value=:token', array(':token'=>$enct));
			$this->cookies->removeCookie('SNID');
			return true;
		}
		return false;
	}

	public function createLoginTokensTable() {
		try {
			$this->DB::query('CREATE TABLE logintokens (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				value VARCHAR(255) NOT NULL,
				user_id INT(6) NOT NULL 
		)');
			return true;
		} catch (Exception $e) {
			echo '<script>console.log("Failed to create login tokens table.");</script>';
			return false;
		}
	}

	public function createUserTable() {
		try {
			$this->DB::query('CREATE TABLE users (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(32) NOT NULL,
		password VARCHAR(60) NOT NULL,
		email TEXT NOT NULL,
		role VARCHAR(32) NOT NULL,
		birthdate DATE NOT NULL,
		verified TINYINT(1) NOT NULL,
		profileimg VARCHAR(255)

)');
			return true;
		} catch (Exception $e) {
			echo '<script>console.log("Failed to create user table.");</script>';
			return false;
		}
	}

	public function createToken() {
		try {
			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			return $token;
		} catch(Exception $e) {
			return null;
		}
	}

	public function encryptedToken($token) {
		try {
			$enc = sha1($token);
			return $enc;
		} catch(Exception $e) {
			return null;
		}
	}

	public function createLoginCookes($userid) {
		try {
			$token = $this->createToken();
			$encryptedToken = $this->encryptedToken($token);
			$this->cookies->setCookie('SNID', $token, $this->cookies->TIME_HOUR);
			if ($this->DB::query('SELECT id FROM logintokens WHERE user_id=:userid LIMIT 1', array(':userid'=>$userid))) {
				$this->DB::query('UPDATE logintokens SET value=:enctoken WHERE user_id=:userid', array(':enctoken'=>$encryptedToken, ':userid'=>$userid));
			} else {
				$this->DB::query('INSERT INTO logintokens VALUES (\'\', :token, :user_id)', array(':token'=>$encryptedToken, ':user_id'=>$userid));
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}