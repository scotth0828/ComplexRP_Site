<?php
include 'libraries/cookies.php';
include 'libraries/account.php';

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
											ErrorMessage('You are too young to make an account with us!');
										}
									} else {
										ErrorMessage('Passwords do not match!');
									}
								} else {
									ErrorMessage('Password must have an uppercase letter, a lowercase letter, a special character, and be greater than 8 characters!');
								}
							} else {
								ErrorMessage('Too little or too many characters for a password!');
							}
						} else {
							ErrorMessage('Email already exists!');
						}
					} else {
						ErrorMessage('Email is not valid!');
					}
				} else {
					ErrorMessage('This username already exist!');
				}
			} else {
				ErrorMessage('Username may only be alphanumeric!');
			}
		} else {
			ErrorMessage('Too little or too many characters for a username!');
		}
	} else {
		ErrorMessage('One or multiple fields empty!');
	}

}

function ErrorMessage($errmessage) {
	$username = urlencode($_POST['username']);
	$email = $_POST['email'];
	$birthdate = $_POST['birthdate'];
	$message = 'enc=true&username='.$username.'&email='.$email.'&birthdate='.$birthdate;
	$message = $message . '&errormessage=' . $errmessage;



	header('Location: signup.php?'.urlencode($message));
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