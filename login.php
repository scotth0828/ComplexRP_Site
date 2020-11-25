<?php include 'header.php'; ?>

<content class="container text-center">

<div class="col-md-4 col-md-offset-4 loginform">
	<form action="" method="POST">
		<label id="signinlabel">Sign In</label>
		<div class="form-group">
			<input type="text" class="form-control" id="username" placeholder="Username">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" id="password" placeholder="Password">
		</div>
		<div class="fplink">
			<a href="#">Forgot Password?</a>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>

</content>

<?php include 'footer.php'; ?>