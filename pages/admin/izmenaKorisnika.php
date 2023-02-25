<?php if(!isset($_GET['id'])): ?>
    <h1>Došlo je do greške...</h1>
<?php else: ?>
    <div class="section">
        <div class="container">
            <?php 
                $id = $_GET['id'];
                global $konekcija;
                $upit = $konekcija -> prepare("SELECT k.ime, k.prezime, k.email, k.korisnicko_ime, u.naziv, k.sifra FROM korisnici k JOIN uloga_korisnika u ON k.uloga_id = u.uloga_id WHERE k.korisnik_id = :id");
                $upit -> bindparam(":id", $id);
                $upit -> execute();
                $korisnik = $upit -> fetch();
                //print_r($korisnik);
            ?>
            <div class="row">
          <div class="col-md-12">
            <!-- Billing Details -->
            <form action="" method="post" onsubmit="return false">
            <div class="billing-details">
              <div class="section-title">
                <h3 class="title">Izmena podataka korisnika <u><?php echo $korisnik -> korisnicko_ime ?></u></h3>
              </div>
              <div id="hahaha" class="form-group">
                <input
                  id="poljeIme"
                  class="input"
                  type="text"
                  name="first-name"
                  value="<?php echo $korisnik -> ime ?>"
                />
                <span id="greskaIme" class="greska"></span>
              </div>
              <div id="" class="form-group">
                <input
                  id="poljePrezime"
                  class="input"
                  type="text"
                  name="first-name"
                  value="<?php echo $korisnik -> prezime ?>"
                />
                <span id="greskaPrezime" class="greska"></span>
              </div>
              <div id="" class="form-group">
                <input
                  id="poljeKorisnickoIme"
                  class="input"
                  type="text"
                  name="first-name"
                  value="<?php echo $korisnik -> korisnicko_ime ?>"
                />
                <span id="greskaKorisnickoIme" class="greska"></span>
              </div>
              <div class="form-group">
                <input
                  id="poljeEmail"
                  class="input"
                  type="email"
                  name="email"
                  value="<?php echo $korisnik -> email ?>"
                />
                <span id="greskaEmail" class="greska"></span>
              </div>
              <!-- <div class="form-group">
                <input
                  id="poljeSifra"
                  class="input"
                  type="password"
                  name="password"
                  value="<?php echo $korisnik -> sifra ?>"
                />
                <span id="greskaSifra" class="greska"></span>
              </div>
              <div class="form-group">
                <input
                  id="poljePonovljenaSifra"
                  class="input"
                  type="password"
                  name="password2"
                  value=""
                />
                <span id="greskaPonovljenaSifra" class="greska"></span>
              </div> -->
              <input
              type="button"
              id="dugmeIzmenaKorisnika"
              class="primary-btn order-submit"
              value="Izmeni"
              /></br></br>
              <span id="uspesnaIzmena" class="tekst-green">Uspešno ste promenili podatke!</span>
              <span id="neuspesnaIzmena" class="tekst-crveno"></span>
            </div>
            </form>

            <!-- Order notes -->
            
            <!-- /Order notes -->
          </div>

          <!-- Order Details -->
          
          <!-- /Order Details -->
        </div>
        </div>
    </div>
<?php endif; ?>
    
