<?php

if (isset($_POST['submit'])) {

	require_once 'classes/account.php';
	require_once 'classes/cookies.php';

	$acc = new account();
	$acc->createLoginTokensTable();

	$cookie = new cookies();

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (!empty($username) && !empty($password)) {
		if ($acc->verify($username, $password)) {
			// account verified

			$user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
			echo '<script>alert("'.$user_id.'");</script>';
			$acc->createLoginCookies($user_id);

			header('Location: index.php');
		} else {
			ErrorMessage('Account credentials incorrect or account does not exist!');
		}
	}
}

function ErrorMessage($message) {
	$m = 'enc=true&errormessage='.$message;
	header('Location: login.php?'.$m);
}