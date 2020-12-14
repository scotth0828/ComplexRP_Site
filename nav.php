<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #222831;">
  <a class="navbar-brand" href="index.php">Event Horizon</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Forum</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Repository
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" target="_blank" href="https://github.com/scotth0828/EventHorizon">Site</a>
          <a class="dropdown-item" target="_blank" href="https://github.com/Karutoh/ComplexRP">Gamemode</a>
        </div>
      </li>
      
      <ul class="nav navbar-nav navbar-right">
        <?php
      if (!Account::isLoggedIn()) {
      ?>
        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        <li class="nav-item"><a class="nav-link" href="signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
        <?php
      } else {
      ?>
      <li class="nav-item dropdown dropdown-user">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="dropdown-user-menu-item">
          <?php 
          $id = Account::getData(Account::ID);
          $username = Account::getData(Account::USERNAME);
          $avatar = Account::getData(Account::AVATAR);
          
          echo '<img id="avatar" src="'.$avatar.'"></img>';
          echo '<p>'.$username.'</p>';

           ?>
          </div>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" target="" href="profile.php">Profile</a>
          <a class="dropdown-item" target="" href="signout.php">Sign Out</a>
        </div>
      </li>
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