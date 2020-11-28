<?php
require 'classes/account.php';
$acc = new account();
$acc->signOut();
header('Location: index.php');