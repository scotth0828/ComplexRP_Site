<?php
include 'header.php'; 
include 'libraries/Image.php';

if (!Account::isLoggedIn()) {
	header('Location: index.php');
	die();
}

$id = Account::getData(Account::ID);
echo $id;
if (isset($_POST['submitAvatar']))
  Image::uploadImage('avatarupload', 'UPDATE accounts SET profileimg=:avatarupload WHERE id=:userid', array(':userid'=>$id));
if (isset($_POST['submitBanner']))
  //Image::uploadImage('bannerupload', 'UPDATE users SET profileimg=:bannerupload WHERE id=:userid', array(':userid'=>$id)); // create database for profile
?>

<style>.content { padding: 0px; }</style>

<!-- CONTENT -->

<div class="content">

<div class="profile-editform">

<div id="avatar">
	<form action="" method="post" enctype="multipart/form-data">
	  <label for="avatarlabel">Set your avatar image here</label>
	  <input type="file" name="avatarupload" id="avatarupload" accept='image/jpeg'>
	  <input class="btn btn-primary" type="submit" value="Upload Image" name="submitAvatar">
	</form>
</div>
<hr>
<div id="banner">
	<form action="" method="post" enctype="multipart/form-data">
	  <label for="bannerlabel">Set your banner image here</label>
	  <input type="file" name="bannerUpload" id="bannerupload" accept='image/jpeg'>
	  <input class="btn btn-primary" type="submit" value="Upload Image" name="submitBanner">
	</form>
</div>
<hr>
<div id="biography">
	<form class="form-group">
		<label for="biolabel">Enter your biography here</label>
		<textarea class="form-control" name="message" rows="3" >The cat was playing in the garden.</textarea>
		<input class="btn btn-primary" type="submit" value="Set Biography" name="submitBio">
	</form>
</div>

</div>

</div>

<!-- -->

<?php include('footer.php'); ?>