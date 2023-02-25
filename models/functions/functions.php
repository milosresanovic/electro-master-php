<?php
function selectQuery($table, $upit = false)
{
  global $konekcija;
  if ($upit) {
    $result = $konekcija->query($upit);
  } else {
    $result = $konekcija->query("SELECT * FROM $table");
  }
  return $result->fetchAll();
}
/* Ispis navigacionog menija za korisnika */
function customerNavigation()
{
  $links = selectQuery("stavke_menija");
  $print = "<ul class='main-nav nav navbar-nav'>";
  foreach ($links as $l) {
    if ($l -> link == 'https://milosresanovic.github.io/mrportfolio/index.html')
      $print .= "<li><a href='$l->link'>$l->naziv</a></li>";
    else
      $print .= "<li><a href='index.php?page=$l->link'>$l->naziv</a></li>";
  }
  $print .= `</ul>`;
  return $print;
}
/* Ispis navigacionog menija za admine */
function adminNavigation()
{
  $links = selectQuery("meni_admin");
  $print = "<ul class='main-nav nav navbar-nav'>";
  foreach ($links as $l) {
    $print .= "<li><a href='index.php?page=$l->link'>$l->naziv</a></li>";
  }
  $print .= `</ul>`;
  return $print;
}
/* Funkcija za ispis zvezdica */
function printStars($br)
{
  $print = "";
  for ($i = 0; $i < $br; $i++) {
    $print .= "
          <i class='fa fa-star zvezdica-boja'></i>
        ";
  }
  return $print;
}
/* Funkcija za pretplatu korisnika na email za popust kodove */
function insertEmailForPromocodes($email)
{
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    global $konekcija;
    $datum = date('Y-m-d H:i:s');
    $upit = "INSERT INTO mailing_lista (email, datum_pretplate) VALUES (:email, :datum)";
    $priprema = $konekcija->prepare($upit);
    $priprema->bindParam(":email", $email);
    $priprema->bindParam(":datum", $datum);

    $rezultat = $priprema->execute();
    return $rezultat;
  }
}
/* Funkcija za ispis kategorija proizvoda */
function displayCategories()
{
  $rezultat = selectQuery("kategorije");
  $print = "";
  foreach ($rezultat as $r) : ?>
    <div class="input-checkbox">
      <input class="kategorija-check" type="checkbox" value="<?php echo $r->kategorija_id ?>" id="<?php echo $r->kategorija_id ?>">
      <label for="kategorije<?php echo $r->kategorija_id ?>">
        <!-- <span></span> -->
        <?php echo $r->naziv ?>
      </label>
    </div>
  <?php endforeach;
  return $print;
}
/* Funkcija za ispis brendova proizvoda */
function displayBrands()
{
  $rezultat = selectQuery("brendovi");
  $print = "";
  foreach ($rezultat as $r) : ?>
    <div class="input-checkbox">
      <input class="brend-check" type="checkbox" value="<?php echo $r->brend_id ?>" id="<?php echo $r->brend_id ?>">
      <label for="brendovi<?php echo $r->brend_id ?>">
        <!-- <span></span> -->
        <?php echo $r->naziv ?>
      </label>
    </div>
<?php endforeach;
  return $print;
}
/* Funkcija za proveru da li username vec postoji u bazi */

/* Funkcija za unos novog korisnika */
function insertNewUser($ime, $prezime, $username, $email, $sifra, $kod)
{
  global $konekcija;
  $upit = "INSERT INTO korisnici (ime, prezime, korisnicko_ime, email, sifra, uloga_id, verifikacioni_kod) VALUES (:ime, :prezime, :username, :email, :sifra, 1, :verifikacioni_kod)";
  $priprema = $konekcija->prepare($upit);
  $priprema->bindParam(":ime", $ime);
  $priprema->bindParam(":prezime", $prezime);
  $priprema->bindParam(":username", $username);
  $priprema->bindParam(":sifra", $sifra);
  $priprema->bindParam(":email", $email);
  $priprema->bindParam(":verifikacioni_kod", $kod);

  $rezultat = $priprema->execute();
  return $rezultat;
}
/* Funkcija za unos novog admina */
function insertNewAdmin($ime, $prezime, $username, $email, $sifra)
{
  global $konekcija;
  $upit = "INSERT INTO korisnici (ime, prezime, korisnicko_ime, email, sifra, uloga_id, verifikovan) VALUES (:ime, :prezime, :username, :email, :sifra, 2, 1)";
  $priprema = $konekcija->prepare($upit);
  $priprema->bindParam(":ime", $ime);
  $priprema->bindParam(":prezime", $prezime);
  $priprema->bindParam(":username", $username);
  $priprema->bindParam(":sifra", $sifra);
  $priprema->bindParam(":email", $email);

  $rezultat = $priprema->execute();
  return $rezultat;
}
/* Funkcija za logovanje korisnika, provera da li nalog postoji */
function login($email, $password)
{
  global $konekcija;
  $upit = "SELECT * FROM korisnici k JOIN uloga_korisnika u ON k.uloga_id = u.uloga_id WHERE k.email = :email AND k.sifra = :sifra";

  $priprema = $konekcija->prepare($upit);
  $priprema->bindParam(":email", $email);
  $priprema->bindParam(":sifra", $password);
  $priprema->execute();

  $rezultat = $priprema->fetch();
  return $rezultat;
}
/* Funkcija za prebrojavanje ukupnog broja registrovanih korisnika na sajtu */
function countUsers()
{
  global $konekcija;
  $upit = $konekcija->prepare("SELECT * FROM korisnici WHERE uloga_id = 1");
  $upit->execute();
  $niz = $upit->fetchAll();
  $br = count($niz);
  return $br;
}
/* Funkcija za prebrojavanje ukupnog broja registrovanih korisnika na sajtu */
function countUnreadMessages()
{
  global $konekcija;
  $upit = $konekcija->prepare("SELECT * FROM poruke_za_admina WHERE status_poruke = 0");
  $upit->execute();
  $niz = $upit->fetchAll();
  $br = count($niz);
  return $br;
}
/* Funkcija za prebrojavanje broja racuna */
function countOrders()
{
  global $konekcija;
  $upit = $konekcija->prepare("SELECT * FROM racuni");
  $upit->execute();
  $niz = $upit->fetchAll();
  $br = count($niz);
  return $br;
}
/* Funkcija za sumiranje svih racuna */
function sumOrderPrice()
{
  global $konekcija;
  $upit = $konekcija->prepare("SELECT sum(suma) as suma FROM racuni");
  $upit->execute();
  $suma = $upit->fetch();
  return $suma->suma;
}
?>