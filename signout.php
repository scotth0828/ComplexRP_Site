<?php
require 'libraries/cookies.php';
require 'libraries/account.php';
Account::signOut();
header('Location: index.php');