<?php

require 'libraries/cookies.php';
require 'libraries/account.php';

if ($_GET['code'] == 'signout') {
	Account::signOut();
	header('Location: index.php');
}

if (isset($_POST['follow'])) {
	$userfollowed = $_POST['user'];
	$id = Account::getIDFromType(Account::USERNAME, $userfollowed);
	Account::followUser($id);
	header('Location: profile.php?profile='.$userfollowed);
	die();
}

if (isset($_POST['block'])) {
	$userfollowed = $_POST['user'];
	$id = Account::getIDFromType(Account::USERNAME, $userfollowed);
	Account::blockUser($id);
	header('Location: profile.php?profile='.$userfollowed);
	die();	
}

if (isset($_POST['loginaccount'])) {

	DB::createLoginTokensTable();

	$username = $_POST['username'];
	$password = $_POST['password'];
	$rememberme = '';

	try {
		if (!isset($_POST['rememberme'])) $rememberme = false; else $rememberme = true;
	} catch(Exception $e) {$rememberme = false;}

	if (!empty($username) || !empty($password)) {
		$val = Account::verify($username, $password);
		if (Account::verify($username, $password)) {
			// account verified 

			if($rememberme)
				cookies::setCookie('remembermeusername', $username, cookies::TIME_MONTH);
			else
				cookies::removeCookie('remembermeusername');
				
			$user_id = Account::getIDFromType(Account::USERNAME, $username);

			Account::createLoginCookies($user_id);

			header('Location: index.php');
			die();
		}
	}
	cookies::removeCookie('remembermeusername');
	LoginErrorMessage('Account credentials incorrect or account does not exist!' . $val);
}

if (isset($_POST['register'])) {

	DB::createUserTable();

	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirmpassword = $_POST['confirmpassword'];
	$birthdate = $_POST['birthdate'];

	if (!empty($email) && !empty($username) && !empty($password) && !empty($confirmpassword) && !empty($birthdate)) {
		// Fields are not empty
		if (strlen($username) >= 6 && strlen($username) <= 32) {
			// enough characters
			if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
				// Username matches regex.
				if (DB::getData('accounts', 'username', 'username', $username) == NULL) {
					// Username does not exist. Continue.
					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						// Email is valid
						if (DB::getData('accounts', 'email', 'username', $username) == NULL) {
							// Email does not exist! Continue.
							if (strlen($password) >= 6 && strlen($password) <= 60) {
								// password within size.
								$upper = preg_match('@[A-Z]@', $password);
								$lower = preg_match('@[a-z]@', $password);
								$number = preg_match('@[0-9]@', $password);
								$special = preg_match('@[^\w]@', $password);
								if ($upper && $lower && $number && $special) {
									// Password is strong. Continue.
									if ($password == $confirmpassword) {
										// Passwords match. Continue.
										if (userAge($birthdate) >= 13) {
											// User is old enough. Continue.
											$timestamp = date('Y-m-d h-m-s');
											$hash = password_hash($password, PASSWORD_BCRYPT);
											DB::setData('accounts', array('RegistrationDate'=>$timestamp, 'Username'=>$username, 'Password'=>$hash, 'Email'=>$email, 'Role'=>'member', 'BirthDate'=>$birthdate, 'Verified'=>'0', 'ProfileImg'=>''));

											// ACCOUNT CREATED AND LOGGED IN

											$user_id = Account::getIDFromType(Account::USERNAME, $username);
											Account::createLoginCookies($user_id);

											header('Location: index.php');
											die();
										} else {
											RegisterErrorMessage('You are too young to make an account with us!');
										}
									} else {
										RegisterErrorMessage('Passwords do not match!');
									}
								} else {
									RegisterErrorMessage('Password must have an uppercase letter, a lowercase letter, a special character, and be greater than 8 characters!');
								}
							} else {
								RegisterErrorMessage('Too little or too many characters for a password!');
							}
						} else {
							RegisterErrorMessage('Email already exists!');
						}
					} else {
						RegisterErrorMessage('Email is not valid!');
					}
				} else {
					RegisterErrorMessage('This username already exist!');
				}
			} else {
				RegisterErrorMessage('Username may only be alphanumeric!');
			}
		} else {
			RegisterErrorMessage('Too little or too many characters for a username!');
		}
	} else {
		RegisterErrorMessage('One or multiple fields empty!');
	}

}

function RegisterErrorMessage($errmessage) {
	$username = urlencode($_POST['username']);
	$email = $_POST['email'];
	$birthdate = $_POST['birthdate'];
	$message = 'enc=true&username='.$username.'&email='.$email.'&birthdate='.$birthdate;
	$message = $message . '&errormessage=' . $errmessage;



	header('Location: signup.php?'.urlencode($message));
}

function LoginErrorMessage($message) {
	$m = 'enc=true&errormessage='.$message;
	header('Location: login.php?'.$m);
}

function passwordStrength($password) {
	$upper = preg_match('@[A-Z]@', $password);
	$lower = preg_match('@[a-z]@', $password);
	$number = preg_match('@[0-9]@', $password);
	$special = preg_match('@[^\w]@', $password);
	if (!$upper || !$lower || !$number || !$special || strlen($password) < 8)
		return true;
	return false;
}

function userAge($date) {
	$year = explode('-', $date);
	$age = date('Y') - $year[0];
	if (date('md') < date('md', strtotime($date))) { 
       return $age - 1; 
   	}
   return $age;
}