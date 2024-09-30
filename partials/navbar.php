<?php
require __DIR__ . '/../vendor/autoload.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="<?= settings()['logo'] ?>" alt="Logo" style="height: 40px; width: 40px; border-radius: 50%;">
      Tigercommerce
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
          <li class="nav-item position-relative">
            <a class="nav-link" aria-disabled="true" href="cart.php">
              <i class="bi bi-cart4"></i>
                
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge">                
                </span>
            </a>
          </li>
        <?php } ?>
        </ul>
      <div class="navbar-search me-auto mx-5">
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn" type="submit">Search</button>
        </form>
      </div>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
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
                <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
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
