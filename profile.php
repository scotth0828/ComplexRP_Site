<?php
require 'header.php';
$userprofile = false;
$avatar = 'images/default_avatar.jpg'; // default avatar
$tag = '@default';
$bio = 'deafault bio description';

$userfollowed = '';

DB::createFollowerTable();

if (isset($_GET['profile']) == NULL) {
  if (Account::isLoggedIn()) {
    $userprofile = true;
    $userfollowed = $username;
    $avatar = Account::getData(Account::AVATAR);
    $tag = '@' . Account::getData(Account::USERNAME);
  } else {
    header('Location: index.php');
    die();
  }
} else {
  $username = $_GET['profile'];
  $id = Account::getIDFromType(Account::USERNAME, $username);
  if (Account::getData(Account::ID) == $id) {
    $userprofile = true;
    $userfollowed = $username;
  }
  else {
    $userfollowed = $username;
  }

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
          	<p><?php echo count(Account::getFollowers(Account::getIDFromType(Account::USERNAME, $userfollowed))); ?> Followers</p>
          	<p> | </p>
          	<p><?php echo count(Account::getFollowing(Account::getIDFromType(Account::USERNAME, $userfollowed))); ?> Following</p>
          </div>
          <?php if (!$userprofile && Account::isLoggedIn()) { ?>
          <div class="followblock">
            <?php 
            $f = Account::isFollowing(Account::getIDFromType(Account::USERNAME, $userfollowed));
            if ($f != Account::BLOCKED) { ?>
          	<form action="action.php" method="POST">
              <input type="hidden" name="user" value="<?php echo $userfollowed; ?>">
          	  <button name="follow" class="btn btn-primary followbtn">
               <?php
                if ($f != Account::FOLLOWING)
                  echo 'Follow';
                else
                  echo 'UnFollow';
               ?>
              </button>
          	</form>
            <?php } ?>
          	<form action="action.php" method="POST">
              <input type="hidden" name="user" value="<?php echo $userfollowed; ?>">
          	  <button name="block" class="btn btn-primary blockbtn">
                <?php
                if ($f != Account::BLOCKED)
                  echo 'Block';
                else
                  echo 'UnBlock';
                ?>
              </button>
          	</form>
          </div>
          <?php } ?>
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