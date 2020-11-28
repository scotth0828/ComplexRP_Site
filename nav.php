<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #222831;">
  <a class="navbar-brand" href="index.php">ComplexRP</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Repository
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" target="_blank" href="https://github.com/scotth0828/ComplexRP_Site">Site</a>
          <a class="dropdown-item" target="_blank" href="https://github.com/Karutoh/ComplexRP">Gamemode</a>
        </div>
      </li>
      
      <ul class="nav navbar-nav navbar-right">
        <?php
      if (!$acc->isLoggedIn()) {
      ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        <?php
      } else {
      ?>
        <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="signout.php">Sign Out</a></li>
      <?php
      }
      ?>
      </ul>
      
    </ul>
  </div>
</nav>

<script>
  $(document).ready(function() {
    $('.nav-link').each(function() {
      var p = $(location).attr('pathname').split('/');
      var loc = p[p.length-1];
      if (loc === $(this).attr('href')) {
        $(this).css('color', 'white');
      }
    });
  });
</script>