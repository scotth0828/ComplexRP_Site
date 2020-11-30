<?php

if (isset($_POST['submit'])) {

	require_once 'libraries/account.php';
	require_once 'libraries/cookies.php';

	$acc = new account();
	$acc->createLoginTokensTable();

	$cookie = new cookies();

	$username = $_POST['username'];
	$password = $_POST['password'];
	$rememberme = '';

	try {
		if (!isset($_POST['rememberme'])) $rememberme = false; else $rememberme = true;
	} catch(Exception $e) {$rememberme = false;}

	if (!empty($username) || !empty($password)) {
		if ($acc->verify($username, $password)) {
			// account verified

			if($rememberme)
				$cookie->setCookie('remembermeusername', $username, $cookie->TIME_MONTH);
			else
				$cookie->removeCookie('remembermeusername');
				

			$user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
			$acc->createLoginCookies($user_id);

			header('Location: index.php');
		}
	}
	echo '<script>alert("this is true");</script>';
			$cookie->removeCookie('remembermeusername');
			ErrorMessage('Account credentials incorrect or account does not exist!');
}

function ErrorMessage($message) {
	$m = 'enc=true&errormessage='.$message;
	header('Location: login.php?'.$m);
}