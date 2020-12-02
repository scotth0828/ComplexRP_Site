<?php
include 'header.php'; 
include 'libraries/Image.php';

$id = $acc->getID();
if (isset($_POST['submitAvatar']))
  Image::uploadImage('avatarupload', 'UPDATE users SET profileimg=:avatarupload WHERE id=:userid', array(':userid'=>$id));
if (isset($_POST['submitBanner']))
  //Image::uploadImage('bannerUpload', 'UPDATE users SET profileimg=:bannerUpload WHERE id=:userid', array(':userid'=>$id)); // create database for profile
?>

<style>.content { padding: 0px; }</style>

<!-- CONTENT -->

<div class="content">

<div class="profile-editform container-fluid">

<div id="avatar">
	<form action="" method="post" enctype="multipart/form-data">
	  <p>Select image to upload:</p>
	  <input type="file" name="avatarupload" id="avatarupload" accept='image/jpeg'>
	  <input class="btn btn-primary" type="submit" value="Upload Image" name="submitAvatar">
	</form>
</div>
<hr>
<div id="banner">
	<form action="" method="post" enctype="multipart/form-data">
	  <p>Select image to upload:</p>
	  <input type="file" name="bannerUpload" id="bannerUpload" accept='image/jpeg'>
	  <input class="btn btn-primary" type="submit" value="Upload Image" name="submitBanner">
	</form>
</div>
<hr>
<div id="biography"></div>
	<form>
		<p>Enter your biography</p>
		<textarea name="message" rows="10" cols="30">The cat was playing in the garden.</textarea>
		<input class="btn btn-primary" type="submit" value="Set Biography" name="submitBio">
	</form>
</div>

</div>

<!-- -->

<?php include('footer.php'); ?>