<?php include('header.php'); 

$id = $acc->getID();
$avatar = 'images/default_avatar.jpg'; // default avatar
$tag = '@default';
$bio = 'deafault bio description';

if ($id != 0) {
	$tag = '@'.$acc->getUsername($id);
	$filePath = 'avatars/'.$id.'.jpg';
	if (file_exists($filePath)) $avatar = $filePath;
}

?>

<style>.content { padding: 0px; }</style>

<!-- CONTENT -->

<div class="content">

<div class="profilebanner">
	<div class="profileavatar">
		<img src="<?php echo $avatar; ?>"></div>
	</div>
</div>

<div class="profilecontent">
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="tag"><?php echo $tag; ?></div>
      <form action="editprofile.php" method="POST">
      	<button class="btn btn-primary editprofile">Edit Account</button>
      </form>
      <div class="followfollowingcount">
      	<p>0 Followers</p>
      	<p> | </p>
      	<p>0 Following</p>
      </div>
      <div class="followblock">
      	<form action="#" method="POST">
      	<button class="btn btn-primary followbtn">Follow</button>
      	</form>
      	<form action="#" method="POST">
      	<button class="btn btn-primary blockbtn">Block</button>
      	</form>
      </div>
      <div class="bio"><?php echo $bio; ?></div>
    </div>
    <div class="col-md-8">
      Two of two columns
    </div>
  </div>
</div>
</div>

</div>

<!-- -->

<?php include('footer.php'); ?>