<?php
require 'header.php';
$userprofile = false;
$avatar = 'images/default_avatar.jpg'; // default avatar
$tag = '@default';
$bio = 'deafault bio description';

if (isset($_GET['profile']) == NULL) {
  if (Account::isLoggedIn()) {
    $userprofile = true;
    $avatar = Account::getData(Account::AVATAR);
    $tag = '@' . Account::getData(Account::USERNAME);
  } else {
    header('Location: index.php');
    die();
  }
} else {
  $username = $_GET['profile'];
  $id = Account::getIDFromType(Account::USERNAME, $username);

  if ($id != NULL) {
    $avatar = Account::getData(Account::AVATAR, $id);
    $tag = '@' . Account::getData(Account::USERNAME, $id);
  } else {
    header('Location: index.php');
     die();
  }
}

?>

<!-- CONTENT -->

<div class="pcontent">

  <div class="profilebanner">
  	<div  class="profileavatar noselect">
  		<img class="noselect" src="<?php echo $avatar; ?>">
  	</div>
  </div>

  <div class="profilecontent">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="tag"><?php echo $tag; ?></div>
          <?php if ($userprofile) { ?>
          <form action="editprofile.php" method="POST">
          	<button class="btn btn-primary editprofile">Edit Account</button>
          </form>
          <?php } ?>
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
          <div class="profile-posts-container">
            <header><h1>Recent Activity</h1></header>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- -->

<?php include('footer.php'); ?>