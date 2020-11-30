<?php
include 'header.php'; 
include 'classes/Image.php';

$id = $acc->getID();
if (isset($_POST['submitAvatar']))
  Image::uploadImage('fileToUpload', 'UPDATE users SET profileimg=:fileToUpload WHERE id=:userid', array(':userid'=>$id));
?>

<style>.content { padding: 0px; }</style>

<!-- CONTENT -->

<div class="content">

<form action="" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload" accept='image/jpeg'>
  <input type="submit" value="Upload Image" name="submitAvatar">
</form>

</div>

<!-- -->

<?php include('footer.php'); ?>