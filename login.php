<?php include 'header.php';

if (Account::isLoggedIn())
	header('Location: index.php');

$remember = cookies::getCookie('remembermeusername');

function getValue($key) {
	$url = $_SERVER['QUERY_STRING'];
	$decoded = urldecode($url);
	if (strpos($decoded, 'enc=true') !== false) {
		$ar = explode('&', $decoded);
		foreach ($ar as $a) {
			$ex = explode('=', $a);
			if ($ex[1] != NULL && $ex[0] === $key)
				return $ex[1];
		}
	}
	return '';
}

 ?>

<div class="content container text-center">

<div class="col-md-4 col-md-offset-4 loginform">
	<form action="loginaccount.php" method="POST">
		<label id="signinlabel">Sign In</label>
		<div class="loginErrorMessage"><p><?php echo getValue('errormessage'); ?></p></div>
		<div class="form-group">
			<div class="icon-container">
				<i class="fa fa-user icon"></i>
				<input name="username" type="text" class="form-control icon-field" id="username" placeholder="Username" value="<?php if ($remember != NULL) echo $remember; ?>">
			</div>
		</div>
		<div class="form-group">
			<div class="icon-container">
				<i class="fa fa-key icon"></i>
				<input name="password" type="password" class="form-control" id="password" placeholder="Password">
			</div>
		</div>
		<div class="form-group form-check rememeberme">
		    <input name="rememberme" type="checkbox" class="form-check-input remembermeinput">
		    <label class="form-check-label remembermelabel" for="exampleCheck1">Remember me</label>
		  </div>
		<div class="fplink">
			<a href="#">Forgot Password?</a>
		</div>
		<button name="submit" type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>

</div>

<?php

if ($remember != NULL) {
?>
<script>$('.remembermeinput').prop('checked', 'true');</script>
<?php
}

if (getValue('errormessage') === '') {
	?>
	<script>
		$('.loginErrorMessage').remove();
	</script>
	<?php
}
include 'footer.php'; ?>