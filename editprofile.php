<?php include('header.php'); 

?>

<style>.content { padding: 0px; }</style>

<!-- CONTENT -->

<div class="content">

<form action="avatarupload.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="hidden" name="id" value="<?php echo $acc->getID(); ?>">
  <input type="file" name="fileToUpload" id="fileToUpload" accept='image/jpeg'>
  <input type="submit" value="Upload Image" name="submitAvatar">
</form>

</div>

<!-- -->

<?php include('footer.php'); ?>