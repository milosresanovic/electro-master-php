<header>
  <!-- TOP HEADER -->
  <div id="top-header">
    <div class="container">
      <ul class="header-links pull-left">
        <?php if (isset($_SESSION["user"])) : ?>
          <li>
            <a href="register.php">
              <i class="fa fa-user"></i> <?php echo $_SESSION['user']->korisnicko_ime; ?>
            </a>
          </li>
          <li>
            <a href="models/logout.php">
              <i class="fa fa-sign-in"></i>Odjavi se
            </a>
          </li>
        <?php else : ?>
          <li>
            <a href="index.php?page=register">
              <i class="fa fa-user"></i> Registracija
            </a>
          </li>
          <li>
            <a href="index.php?page=login">
              <i class="fa fa-sign-in"></i>Prijava
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>


  <!-- MAIN HEADER -->
  <div id="header">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <!-- row -->
      <div id="" class="row d-flex justify-content-between">
        <!-- LOGO -->
        <div class="col-6">
          <div class="header-logo">
            <?php 
              if(isset($_SESSION['user']) && ($_SESSION['user'] -> uloga_id == 2))
                echo "<a href='index.php?page=adminpanel' class='logo'>";
              else
                echo "<a href='index.php?page=pocetna' class='logo'>";
            ?>
            
              <img src="assets/img/logo.png" alt="logo nase firme electro" />
            </a>
          </div>
        </div>

        <!-- ACCOUNT -->
        <div class="col-6 clearfix">
          <div class="header-ctn">
            
            <!-- Cart -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user']->uloga_id == 2) : ?>
              <div id="maliPrikazKorpe" class="dropdown">
                
              </div>
            <?php else : ?>
              <div id="maliPrikazKorpe" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <i class="fa fa-shopping-cart"></i>
                  <span>Korpa</span>
                  <div class="" id="korpicaBroj" class="qty"></div>
                </a>
                <div id="maliPrikazKorpeStavke" class="cart-dropdown">

                </div>
              </div>
            <?php endif; ?>
            <!-- /Cart -->

            <!-- Menu Toogle -->
            <div class="menu-toggle">
              <a href="#">
                <i class="fa fa-bars"></i>
                <span>Menu</span>
              </a>
            </div>
            <!-- /Menu Toogle -->
          </div>
        </div>
        <!-- /ACCOUNT -->
      </div>
      <!-- row -->
    </div>
    <!-- container -->
  </div>
  <!-- MAIN HEADER -->
</header>