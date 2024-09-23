<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item position-relative">
          <a class="nav-link" aria-disabled="true" href="cart.php">
            <i class="bi bi-cart4"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge">
              6              
            </span>
          </a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      <ul class="navbar-nav mb-2 mb-lg-0">
        <?php
          if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']){ 
            ?>
            <li class="nav-item">
          <a class="nav-link" aria-current="page" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="register.php">Register</a>
        </li>
        <?php            
          }
          else{
            ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION['username']; ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li><a class="dropdown-item" href="settings.php">My Orders</a></li>
            <?php 
            if($_SESSION['role'] == 'admin'){
              echo '<li><a class="dropdown-item" href="admin/">Admin Dashboard</a></li>';
            }
            elseif($_SESSION['role'] == 'vendor'){
              echo '<li><a class="dropdown-item" href="shop/">Vendor Dashboard</a></li>';
            }
            ?>
                
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </li>
            <?php
          }
          ?>
        
      </ul>
    </div>
  </div>
</nav>
