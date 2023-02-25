
<nav id="navigation">
      <!-- container -->
      <div class="container">
        <!-- responsive-nav -->
        <div id="responsive-nav">
            <?php
                //include "functions/functions.php";
                if(isset($_SESSION["user"])){
                  if($_SESSION["user"]->uloga_id == 2)
                    echo adminNavigation();
                  else
                    echo customerNavigation();
                }
                else{
                  echo customerNavigation();
                }

            ?>
        </div>
        <!-- /responsive-nav -->
      </div>
      <!-- /container -->
</nav>