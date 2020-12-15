<?php
require 'libraries/Account.php';
require 'libraries/cookies.php';

?>
<!DOCTYPE html>
	<html>
	<head>
		<title>Event Horizon</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

		<script src="https://kit.fontawesome.com/d756906828.js" crossorigin="anonymous"></script>

		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/profile.css">

		<script>
			var p = $(location).attr('pathname').split('/');
			var pp = p[p.length-1].split('.');
			var ppp = pp[0];
			var pppp = ppp.substr(0,1).toUpperCase() + ppp.substr(1);

			$(document).ready(function() {
				if (pppp === 'Index') pppp = 'Home';
		        document.title = 'Event Horizon - ' + pppp + ' Page';
		    });
			
		</script>

	</head>
	<body>
	<div class="container-fluid col-md-12" style="background-color: #e8e8e8;">
	<?php include 'nav.php'; ?>